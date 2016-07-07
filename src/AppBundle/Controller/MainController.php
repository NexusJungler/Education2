<?php

namespace AppBundle\Controller;

use AppBundle\Entity\classe;
use AppBundle\Entity\eleve;
use AppBundle\Form\classeType;
use AppBundle\Form\eleveType;
use HTML2PDF;
use Libern\QRCodeReader\QRCodeReader;
use PHPQRCode\QRcode;
use AppBundle\Repository\algo;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\HttpFoundation\Response;

class MainController extends Controller
{
    /**
     * @Route("/", name="welcome")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function serverOn(Request $request)
    {
        /****************************************************************************
         * It is simply a convention to add the suffix “Action” to the name of those
         * methods in controllers which are directly exposed via routes, to make such
         * actions better distinguishable from other methods. Technically, “Action“ does
         * not have a meaning at all, the method does not behave differently because
         * there is this suffix. Symfony does not force you to use that suffix, but I would
         * strongly recommend following this convention.
         *******************************************************************************/
        return $this->render('default/server.html.twig', array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
        ));
    }

    /**
     * @Route(
     * path = "/home",
     * name = "index"
     * )
     **/
    public function indexAction()
    {;
        $access = $this
            ->get('security.authorization_checker')
            ->isGranted('ROLE_ADMIN');
        if ($access === true) {
            return $this->render('default\index.html.twig', [
                'classes' => $this->buildNav()
            ]);
        } else {
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }
    }

    /**
     * @Route(
     *       path = "/admin/show/{id}",
     *       name = "readbook"
     * )
     * @param $id
     * @return Response
     */
    public function readbookAction($id)

    {
        $em = $this
            ->getDoctrine()
            ->getEntityManager();
        $student = $em
            ->getRepository('AppBundle:eleve')
            ->find($id);
        $class = $em
            ->getRepository('AppBundle:classe')
            ->find($student->getClasse())
            ->getNom();
        $dscp = $em
            ->getRepository('AppBundle:discipline')
            ->findAll();

        $birth = $student->getAge();
        $now = new \DateTime(date('Y-m-d'));
        $age = $now->diff($birth);

        $success = $em
            ->getRepository('AppBundle:eleve')
            ->getSuccess($id);

       return $this->render('book/readbook.html.twig', [
           'classes' => $this->buildNav(),
           'eleve' => $id,
           'content' => $student,
           'age' => $age,
           'classname' => $class,
           'success' => $success,
           'dcps' => $dscp
       ]);
    }

    public function buildNav()
    {
        /**** Initialistion des variables globales ****/
        $result = [];    $test = [];

        $em = $this
            ->getDoctrine()
            ->getEntityManager();

         $user = $this                  //   $this->get('security.token_storage')->getToken()
             ->getUser()
             ->getId();

        $classes = $em
            ->getRepository('AppBundle:classe')
            ->findByUser($user);  // méthode magique findByX non-implémentée par PhpStorm!


        /**** Récupération des élèves de chaque classe ****/
        foreach ($classes as $value) {

            $id = $value->getId();
            $name = $value->getNom();

            // methode querybuilder
            $reformat = [];
            $query= $em
                ->getRepository('AppBundle:eleve')
                ->getStudentOrdered($id);

            // On se sert de l'id de chaque élève comme clé pour accéder aux informations plutôt que de conserver le tableau indicé de la requête
            foreach($query as $content) {
                $reformat[$content['id']] = [
                    'nom' => $content['nom'],
                    'prenom' => $content['prenom']
                ];
            }
            /* methode findby (Critere, Tri, Limite , Offset)
              $query = $em
                  ->getRepository('AppBundle:eleve')
                  ->findBy(['classe' => $value->getId()],
                ['nom' => 'asc']);             */

            $result[$name] = $query;
            $test[$name] = ['id' =>$id, 'eleves' => $reformat];   // on inclut l'id de la classe dans le tableau d'informations
        }
        return $result;
    }

    /**
     * @Route(
     * path = "/loadcat",
     * name = "loadcat"
     * )
     * @Method({"POST"})
     *
     **/
    public function loadCategorie()
    {
        $encoder = [new JsonEncoder()];
        $normalizer = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizer, $encoder);

        $em = $this
            ->getDoctrine()
            ->getManager();

       $categories = $em
            ->getRepository('AppBundle:categorie')
            ->findByDiscipline($_POST['dscp']);

        $result = $serializer->serialize($categories, 'json');
        return new response($result);
    }

    /**
     * @Route(
     * path = "/loadcomp",
     * name = "loadcomp"
     * )
     * @Method({"POST"})
     *
     **/
    public function loadCompetence()
    {
        $encoder = [new JsonEncoder()];
        $normalizer = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizer, $encoder);

        $em = $this
            ->getDoctrine()
            ->getManager();

       $competences = $em
            ->getRepository('AppBundle:Competence')
            ->getCompetenceDql($_POST['dscp'], $_POST['cat']);

        if(isset($_POST['stud'])) {
            $validation = $em
                ->getRepository('AppBundle:validation')
                ->getReport($_POST['stud'], $_POST['cat']);
            foreach ($validation as $value) {
                $value->setSuccessone($value->getSuccessone()->format('d/m/Y'));
                $value->setSuccesstwo($value->getSuccesstwo()->format('d/m/Y'));
                $value->setSuccesstree($value->getSuccesstree()->format('d/m/Y'));
            }
            $data = [$competences, $validation];
        }  else {
            $data = $competences;
        }

        $result = $serializer->serialize($data, 'json');
        return new response($result);
    }

    /**
     * @Route(
     * path = "/loadstud",
     * name = "loadstud"
     * )
     * @Method({"POST"})
     *
     **/
    public function loadStud()
    {
        $encoder = [new JsonEncoder()];
        $normalizer = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizer, $encoder);

        $em = $this
            ->getDoctrine()
            ->getManager();

        $data = $em
            ->getRepository('AppBundle:eleve')
            ->find($_POST['stud']);

        $success = $em
            ->getRepository('AppBundle:eleve')
            ->getSuccess($_POST['stud']);
        $attribut_to_insert = ',"succes":' . $success;
        // L'ajout dynamique de la propriété à l'instance ne fonctionne pas! On va donc rajouter la chaine correspondante après la sérialisation de l'objet au format json.

        $now = new \DateTime(date('Y-m-d'));
        $age = $now->diff($data->getAge());
        $data->setAge($age->format('%Y ans et %m mois'));

        $result = $serializer->serialize($data, 'json');
        $result = substr_replace($result, $attribut_to_insert, -1, 0);
        return new response($result);
    }

    /**
     * @Route(
     *       path = "/admin/addclass",
     *       name = "addclass"
     * )
     * @param Request $request
     * @return Response
     */
    public function addClassAction(Request $request)

    {
        $error = 0;
        $em = $this
            ->getDoctrine()
            ->getManager();

        $class = new classe();
        $class->setUser($this->getUser());
        // Attention, le paramètre attendu est un objet de type user (->getId() génére une erreur)

        $form = $this
            ->get('form.factory')
            ->create(new classeType(), $class);
            /*
             * Sans l'externalisation du formulaire, $this->get('form.factory')->createBuilder('form', $class)->add(xxx)->getForm()
             * La création du formulaire est réduit à une seule ligne!
             * Il est préférable de respecter le nom de l'entité référence.
             */

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);        // $this->check
            if ($form->isValid()) {
                $em->persist($class);
                $em->flush();
                return $this->redirect($this->generateUrl('index'));
            } else {$error = 1;}
        }
        return $this->render('form/addclass.html.twig', [
            'form' => $form->createView(),
            'classes' => $this->buildNav(),
            'test' => $class,
            'error' => $error
        ]);
    }

    /**
     * @Route(
     *       path = "/admin/addstudent",
     *       name = "addstudent"
     * )
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function addStudentAction(Request $request)

    {
        $error = 0;
        $em = $this
            ->getDoctrine()
            ->getManager();

        $student = new eleve();
        $form = $this->createForm(new eleveType(), $student);

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $ext =  $form['photo']->getData()->guessExtension();  // nécessaire d'activer l'extension php_fileinfo.dll dans php.ini
                $fileName = md5(uniqid()).'.' . $ext;   // équivaut à bin2hex(openssl_random_pseudo_bytes(16));
                $student->setPhoto($fileName);
                $uploadDir = $this->getParameter('upload_dir');
                $form['photo']->getData()->move($uploadDir, $fileName);  //  équivaut à move_uploaded_file($_FILES['eleve']['tmp_name']['photo'], $uploadDir . $fileName);
                $em->persist($student);
                $em->flush();
                return $this->redirect($this->generateUrl('index'));
            } else {$error = 1;}
        }
        return $this->render('form/addstudent.html.twig', [
            'form' => $form->createView(),
            'classes' => $this->buildNav(),
            'data' => $student,
            'error' => $error
        ]);
    }

    /**
     * @Route(
     *       path = "/admin/createdoc",
     *       name = "createdoc"
     * )
     **/
    public function createDocAction(){
        $em = $this
            ->getDoctrine()
            ->getManager();

        $dscp = $em
            ->getRepository('AppBundle:discipline')
            ->findAll();

        $user = $this
            ->getUser()
            ->getId();

        $landing = $em
            ->getRepository('AppBundle:classe')
            ->findByUser($user);

        return $this->render('book/createdoc.html.twig', [
            'classes' => $this->buildNav(),
            'dcps' => $dscp,
            'clid' => $landing
        ]);
    }

    /**
     * @Route(
     *       path = "/admin/sendpdf",
     *       name = "sendpdf"
     * )
     * @Method({"POST"})
     **/
    public function sendPdfAction(){

        $content = json_decode('['. $_POST['doc']. ']');
        $classId = $content[0];

        $em = $this
            ->getDoctrine()
            ->getManager();

        $class_students = $em
            ->getRepository('AppBundle:eleve')
            ->getStudentOrdered($classId);

        $username = $this
            ->getUser()
            ->getName();

        $classname = $em
            ->getRepository('AppBundle:classe')
            ->find($classId)
            ->getNom();

        $cptcs = $em
            ->getRepository('AppBundle:Competence');

        /*echo getcwd();
        $this->get('kernel')->getRootDir() . '/../web/css/pdfstyle.css')
        $this->container->getParameter('kernel.root_dir')
        */
        $style = '<style type="text/Css">' . file_get_contents('css/pdfstyle.css') . '</style>';
        $pdf_build = $style;
        $nbrItem = $content[1];

        for($j=0; $j<count($class_students); $j++) {
            $qrc = $classId .'|' .  $class_students[$j]['id'] . '|' . date("Y-m-d") . '|' . $nbrItem . '|';
            $path =  'temp/qrc' . $j. '.png';
            for($i=0; $i<$nbrItem; $i++) {
                $qrc .=  $content[$i+2] . '|' ;
            }
            $code = new QRcode();
            $code->png($qrc, $path, 'Q', 3);

            $entete = '<page><h1>Evaluation LPC ASH</h1><img class="qrcode" src="' . $path . '"><table class="reference"><tr><td style="width: 30%;">Classe: <span>' .$classname. '</span></td> <td>Professeur: <span>'. $username .'</span></td></tr><tr><td style="width: 30%;">Date: <span>' . date("d-m-Y") . '</span></td><td>Eléve: <span>' . $class_students[$j]['prenom'] . ' ' . $class_students[$j]['nom'] .'</span></td></tr></table>';
            $table = '<table class="content">';
            for($i=0; $i<$nbrItem; $i++) {
                $table .= '<tr><td style="font-size:70px;height:100px;">' . ($i+1) .'|</td><td class="intitule">' . $cptcs->find($content[$i+2])->getIntitule() .  '</td><td><img src="images/emptycells.png"></td></tr>';
            }
            $table .= '</table></page>';
            $pdf_build .= $entete . $table;
        }
        $html2pdf = new HTML2PDF('P','A4','fr');
        $html2pdf->WriteHTML($pdf_build);
        for($i=0; $i<count($class_students); $i++) {
            unlink('temp/qrc' . $i. '.png');
        }
        return $html2pdf->Output('Evaldoc'.date('d-m-Y') .'.pdf');
    }

    /**
     * @Route(
     *       path = "/admin/readdoc",
     *       name = "readdoc"
     * )
     **/
    public function readPdfAction(){
        $em = $this
            ->getDoctrine()
            ->getManager();
        $readfile = new algo();

        chdir('temp');
        //exec('convert -density 500 Evaldoc.pdf[7] -crop 490x480+250+310 Qrcod.bmp');   //  490x480+280+390
        //exec('convert -density 100 Evaldoc.pdf[7] -crop 215x580+585+210 Cell.bmp');  // 215x580+570+240
        //$readfile->pass_to_black();

        // extraction données qrcode
        $data = [];  $tableCell = [];
        $offset = 0;
        $key = ['classe', 'eleve', 'date', 'nbrItem', 'competences'];
        $qrcode = new QRCodeReader();
        $responseText = $qrcode->decode("Qrcod.png");
        if (!$responseText) {
            echo 'Illisibilité du Qrcode!';
        } else {
            for ($i = 0; $i < 4; $i++) {
                $pos = strpos($responseText, '|', $offset);
                $item = substr($responseText, $offset, $pos - $offset);
                if ($i < 3) {
                    $data[$key[$i]] = $item;
                } else {
                    $data[$key[$i]] = intval($item);
                }
                $offset = $pos + 1;
            }
            $stud = $em
                ->getRepository('AppBundle:eleve')
                ->find($data['eleve']);
            $class = $em
                ->getRepository('AppBundle:classe')
                ->find($data['classe']);

            $data['eleve'] = $stud->getPrenom() . ' ' . $stud->getNom();
            $data['classe'] = $class->getNom();
            //$readfile->convert_to_monochrome();
            for ($i = 0; $i < $data['nbrItem']; $i++) {
                $tableCell[$i] = $readfile->readCell($i);
                $posCell[$i] = $readfile->getposition($i*100);
            }
            $wordCell = [];
            for ($j = 0; $j < $data['nbrItem']; $j++) {
                $wordCell[$j] = '';
                for ($i = 1; $i < 6; $i++) {
                    if ($tableCell[$j][$i] >= 44) {
                        $wordCell[$j] .= '1';
                    } else {
                        $wordCell[$j] .= '0';
                    }
                }
            }
            // Liaison avec les compétences
            for ($i = 0; $i < $data['nbrItem']; $i++) {
                $cut = strpos($responseText, '|', $offset);
                $compID = substr($responseText, $offset, $cut - $offset);
                $data['competences'][$compID] = $readfile->getIntValue($wordCell[$i]);
                $offset = $cut + 1;
            }

        }
            return $this->render('book/readpdf.html.twig', [
                'classes' => $this->buildNav(),
                'qrc' => $tableCell,
                'pos' => $posCell
            ]);
    }

}
