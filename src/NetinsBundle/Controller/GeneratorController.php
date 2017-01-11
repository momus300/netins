<?php

namespace NetinsBundle\Controller;

use NetinsBundle\Entity\Users;
use NetinsBundle\Utils\Generator\CSV;
use NetinsBundle\Utils\Generator\Files;
use NetinsBundle\Utils\Generator\Generator;
use NetinsBundle\Utils\Generator\XML;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class GeneratorController extends Controller
{
    const NUM_INIT_RECORDS = 2;

    /**
     * Listuje wszystkie pliki i rekordy w bazie danych
     * Zmiana pod nowy branch - dopisek nowych zmian
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listFilesAction()
    {
        $files = (new Files())->getFiles();

        $users = $this->getUsersFromDB();

        return $this->render('@Netins/Generator/files.html.twig', ['files' => $files, 'users' => $users]);
    }

    /**
     * Usuwanie utworzonych plikow CSV i XML
     * Czyszczene z dodanych rekordow do bazy danych
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function clearAction()
    {
        (new Files())->clear();

        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery("DELETE FROM NetinsBundle:Users u WHERE u.id > " . self::NUM_INIT_RECORDS);
        $query->execute();

        return $this->forward('NetinsBundle:Generator:listFiles');
    }

    /**
     * Na podstawie CSVki tworzy plik XML i dodaje rekordy do bazy
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function generateForCsvAction()
    {
        $users = $this->getUsersFromCsv();

        $Files = new Generator();
        $Files->addGenerator(new XML());
        $Files->generate($users);

        $this->addToDataBase($users);

        return $this->forward('NetinsBundle:Generator:listFiles');
    }

    /**
     * Na podstawie XMLa tworzy plik CSV i dodaje rekordy do bazy
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function generateForXmlAction()
    {
        $users = $this->getUsersFromXml();

        $Files = new Generator();
        $Files->addGenerator(new CSV());
        $Files->generate($users);

        $this->addToDataBase($users);

        return $this->forward('NetinsBundle:Generator:listFiles');
    }

    /**
     * Na podstawie bazy danych tworzy pliki XML i CSV
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function generateForDBAction()
    {
        $users = $this->getUsersFromDB();

        $Files = $this->get('files.generator');
        $Files->addGenerator(new XML());
        $Files->addGenerator(new CSV());
        $Files->generate($users);

        return $this->forward('NetinsBundle:Generator:listFiles');
    }

    private function getUsersFromDB()
    {
        $users = $this->getDoctrine()->getManager()
            ->getRepository('NetinsBundle:Users')
            ->findAll();

        if (!$users) {
            throw $this->createNotFoundException(
                'Not found'
            );
        }

        return $users;
    }

    private function getUsersFromCsv()
    {
        return (new CSV())->getData(Files::CSV_FILE);
    }

    private function getUsersFromXml()
    {
        return (new XML())->getData(Files::XML_FILE);
    }

    /**
     * @param array $users
     */
    private function addToDataBase(array $users)
    {
        $em = $this->getDoctrine()->getManager();

        foreach ($users as $user) {
            $User = new Users();
            $User->setNumber($user['Number']);
            $User->setName($user['Name']);
            $User->setSurname($user['Surname']);
            $User->setAge($user['Age']);

            $em->persist($User);
        }

        $em->flush();
    }
}
