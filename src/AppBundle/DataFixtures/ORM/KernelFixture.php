<?php

namespace AppBundle\DataFixtures\ORM;
use Doctrine\Common\DataFixtures\AbstractFixture;

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class KernelFixture extends AbstractFixture 
{
    public $executionTimeFromStart; 
    public $executionTime; 
    
    
    public function load(\Doctrine\Common\Persistence\ObjectManager $manager) {
        //Optimisation du temps de traitement
        $manager->getConnection()->getConfiguration()->setSQLLogger(null);
    }
    
    public function startDebug()
    {
        $timestamp = $this->microtime_float();
        $this->setExecutionTime($timestamp);
        $this->setExecutionTimeFromStart($timestamp);
    }
    
    public function displayDebugInfos($message=false) {
        
        if ($message) {
            echo "------  $message  --------\n";
        }
        echo "Memory Usage: " . round(xdebug_memory_usage()/1048576, 3) . " MB \n"; 
        
        $times = $this->checkExecutionTime();
        echo 'Execution Time: ' . round(($times['execution'] * 1000),3) . " ms \n";
        echo 'Elapsed Time: ' .  round(($times['elapsed'] * 1000),3) . " ms \n";
    }
    
    
    public function checkExecutionTime()
    {
        $timestamp = $this->microtime_float();
        $execution =  $timestamp - $this->getExecutionTime();
        $this->setExecutionTime($timestamp);
        
        return array(
            'execution' => $execution,
            'elapsed' => $timestamp - $this->getExecutionTimeFromStart(),
        );
    }
    
    public function microtime_float()
    {
        list($usec, $sec) = explode(" ", microtime());
        return round(((float)$usec + (float)$sec),3);
    }
    
     public function setExecutionTime($timestamp) 
    {
        $this->executionTime = $timestamp;
        return $this;
    }
    
    public function getExecutionTime() 
    {
        return $this->executionTime;
    }
    
    
    public function setExecutionTimeFromStart($timestamp) 
    {
        $this->executionTimeFromStart = $timestamp;
        return $this;
    }
    
    public function getExecutionTimeFromStart() 
    {
        return $this->executionTimeFromStart;
    }
    
    
    //////////////////////////
    // METHODES UTILITAIRES //
    //////////////////////////
    
    /**
     * Renvoi un élément au hasard pris dans le tableau array
     */
    public function randomElem($array)
    {
        return $array [ rand (0, count($array)-1 ) ];
    }
    
    /**
     * Renvoi une chaine aléatoire
     */
    public function getSomeString($nbmax)
    {
        $jul = 
        "l’organisme national le plus représentatif de la normalisation dans son pays.".
        "Un seul organisme par pays est accepté en qualité de membre".
        "Les comités membres sont en droit de participer aux travaux et exercent pleinement leurs droits de vote dans le cadre de tout comité".
        "technique et de tout Comité chargé de l’élaboration d’orientations politiques".
        "processus consistant à harmoniser les pratiques techniques de tout type, que ce soit au niveau national". 
        "régional ou international. Ce processus traite de questions techniques, est facilité par les normes et souvent employé à l’appui des objectifs de politique.".
        "document qui énonce les caractéristiques de produits, procédés et méthodes de production, y compris les dispositions administratives qui s’y appliquent, ".
        "dont I’observation est obligatoire. II peut traiter en partie ou en totalité de terminologie, de symboles, de prescriptions en matière d’emballage, de marquage ou d’étiquetage, ".
        "pour un produit, un procédé ou une méthode de production donnés"
        ;
                
        $indice = rand (0, strlen($jul));
        $nbCar = rand (30, $nbmax);

        return $this->clean(substr($jul , $indice , $nbCar));
    }
    
    /**
     * Nettoie une châine
     */
    public function clean($string) {
//       $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
        
        $result = trim( preg_replace(
           "/[^a-z0-9']+([a-z0-9']{1,3}[^a-z0-9']+)*/i",
           " ",
           " $string "
        ));
        
       return (empty($result)) ? 'Texte test' : $result;
    }
    
    /**
     * Créé une date aléatoire entre 2010 et 2017
     */
    public function createRandomDatetime() {
        $month = rand(1, 12);
        $year = rand(2010, 2017);
        $day = rand(1, 28);

        return  \DateTime::createFromFormat('d-m-Y', "$day-$month-$year");
    }
    
    /**
     * Créé une date aléatoire entre 2010 et 2017
     */
    public function createDatetimeFromDatetime(\Datetime $datetime, $period) {

        return  $datetime->add(new \DateInterval('P'.$period.'D'));
    }
    
    /**
     * Créé un booléen aléatoire
     */
    public function createRandomBoolean() {
        return rand(0,1) ? true : false;
    }
    
    
    /**
     * Retourne un nom au hasard
     */
    public function getRandomName() {
        
        $list = explode(" ", $this->getFirstnamesList());
        return $list[rand('0', sizeof($list)-1)];
    }
    
    /**
     * Retourne une liste de prénom
     */
    public function getFirstnamesList() {
        //return "Abel Abigail";
        return "Aaron Abdullah Abel Abigail Abraham Achille Adam Adame Adel Adem Adil Adonis Adrian Adriano Adrien Ahmed Aiden Aime Aimen Akim Alain Alan Alann Alban Alberic Albert Albin Alcide Aldric Alec Alessandro Alessio Alex Alexander Alexandre Alexi Alexis Alexy Alfred Alix Allan Aloïs Aloys Alvin Amadou Amaël Amaury Ambroise Amin Amine Amir Anaël Anas Anatole Anderson Andoni André Andréa Andréas Andrew Andy Ange Angelo Anis Aniss Annibal Anouar Anselme Anthonin Anthony Antoine Anton Antoni Antonin Antonio Antony Apollinaire Ariel Aristide Armand Armel Arnaud Arno Arsene Arthur Arthus Artus Aubert Aubin Aubry Audric Auguste Augustin Aurèle Aurélien Austin Auxence Avery Axel Aymen Aymeric Aymerick Ayrton AzizBabacar Bader Badis Bakari Balthazar Bambo Baptist Baptiste Baptistin Baran Barnabe Barthelemy Bartholome Basil Basile Bastian Bastien Batiste Battiste Baudoin Baudouin Baudry Bayron Bazile Bekir Belaid Ben Benedict Beni Benjamin Benjamyn Benji Benoît Benoit-Joseph Béranger Berkan Berkant Berkay Bernard Bertrand Bilaly Billel Billy Birama Blaise Bob Boran Boris Bosco Boubaker Brad Bradley Brady Brahim Brandon Brayan Brayton Brendan Brendon Briac Brian Brice Brieuc Brieux Bruce Bruno Bryan Bryce Bryton Byron Caleb Calixte Calliste Calvin Cameron Camil Camille Can Caner Cantin Carl Carlito Carlo Carlos Carter Casey Casimir Cassandre Cédric Cédrick Céleste Célestin Célian Célien Celil Célio Celyan Cem Cengiz Cenzo Césaire Cesar Ceylan Cham Charlélie Charlély Charles Charles-Alexandre Charles-Antoine Charles-Edouard Charles-Elie Charles-Henri Charles-Hubert Charles-Hugo Charles-Louis Charley Charli Charlie Charly Chayan Chayanne Check Cheick Cheik Chems-Eddine Cherif Chrétien Chris Christ Christian Christin Christofer Christophe Christopher Christy Chrys Clarence Claude Claudien Claudio Clayton Clément Clémentin Cléo Clotaire Clovis Cody Colas Colin Colomban Come Conrad Constand Constant Constantin Constantino Corantin Corenthin Corentin Corentyn Corey Coriolan Corto Cosme Costa Cristiano Curtis Cyprien Cyriac Cyrian Cyriaque Cyril Cyrille Cyrus Daivy Dalil Damian Damien Dan Dani Daniel Danilo Danis Dann Danny Dante Dany Danyl Daoud Dara Darel Darell Dari Darien Dario Darius Darko Darren Darryl Daryl Dave David Davide Davidson Davut Davy Dawson Dayan Dayane Daynis Dean Dembo Denilson Denis Deniz Dennis Dennys Denovan Deny Denys Denzel Désiré Devran Devrim Devy Dewi Deyan Diabe Diako Didier Diego Dietrich Dieudonne Dilan Dilane Dilhan Dimitri Dimitry Dino Diogo Diyar Djamal Djamil Djason Djeson Djezon Djimmy Djoe Djordan Dogan Dogukan Dohan Domenico Dominique Donald Donatien Donavan Doniphan Donovan Doran Dorian Doriano Doryan Douglas Dragan Drice Dryss Drystan Duane Duncan Dursun Dwayne Dylan Dylane Eddie Eddy Eden Edern Edgar Edgard Edmond Edouard Edson Eduardo Edward Edwin Eitan Ekrem Elfried Elian Elias Eliaz Elie Elies Eliesse Elijah Elio Elior Eliot Eliott Elisée Elliot Elliott Eloan Eloi Elouan Elouane Elouen Elric Elvin Elvis Ely Elyas Elyes Elyess Emanuel Emeric Emerick Emil Emile Emilian Emiliano Emilien Emilio Emin Emir Emircan Emmanuel Emrecan Emric Emrick Emrys Endy Eneko Enes Enis Enki Ennio Enrick Enrique Enzo Ephraim Erasme Eray Erdem Erdogan Eren Ergun Eric Erick Erik Erkan Ernest Eros Ervin Erwan Erwann Erwin Esaie Esteban Estebane Estevan Estève Etan Ethan Ethane Ethaniel Ethann Eugène Evan Evann Evans Even Evens Evrard Ewan Ewann Ewen Ewenn Eyal Eymeric Eytan Fabian Fabien Fabio Fabrice Fabrizio Faizan Fantin Faouzi Farah Fares Farid Faudel Faustin Faycal Federico Fedi Felicien Felipe Felix Ferat Ferdi Ferdinand Ferhat Fernand Fernando Ferreol Filip Filipe Filippo Fiorenzo Firat Firmin Flavian Flavien Flavio Fletcher FloranFréddy Floréal Florent Florentin Florentino Florestan Florian Floriant Florien Florient Florimond Florin Floris Floryan Floyd Fodie Folco Fouad Foucauld Foucault Foulques France Francesco Francescu Francisco Francisque Franck Francky Franco François Francois-Baptiste Francois-Louis Francois-Xavier Frank Franklin Frantz Franz Fred Freddy Fredy Furkan Gabin Gabriel Gabriele Gaby Gaël Gaëtan Gaethan Galdric Galien Ganaël Gaoussou Garris Garry Gary Gaspar Gaspard Gaston Gatien Gaulthier Gaultier Gauthier Gautier Gauvain Gavin Gaya Gaylor Gaylord Gebril Gedeon Geoffray Geoffrey Geoffroy George Georges Georgio Gerald Gérard Geraud Gérémy Germain Gerson Géry Ghislain Giacomo Giani Gianni Gianny Gibril Gildas Gilian Gilles Gillian Gino Giovani Giovanni Giovanny Giovany Giraud Gislain Giuliano Giulio Giuseppe Glen Glenn Gloire Godefroy Godwin Goeffroy Gonzague Goran Gordon Goulwen Graig Greg Grégoire Grégor Grégori Grégory Guenael Guéric Guerlain Guerlin Guerric Guewen Guilain Guilhaume Guilhem Guilian Guiliann Guillaume Guillem Guillian Guirec Gurkan Gurvan Gurwan Gustave Gustin Guy Guylian Guyllian Gweltaz Gwen Gwenaël Gwendal Gwenn Gwenvaël Hachim Hadil Hadrian Hadrien Hady Haidar Haider hailey Haim Haitham Hakim Hamada Hameza Hamilton Hamine Hamza Handy Hani Hans Hany Harald Hari Haris Harisson Harley Harold Haron Harris Harrison Harry Harvey Hasni Hassen Hatem Hector Heddi Heddy Hedi Hedy Heidi Helian Helias Helie Helio Helios Helori Helwan Hemza Hendrick Hendy Henoc Henri Henrique Henry Henzo Herbert Herman Hermann Hermes Herve Herwan Hery Hicham Hichame Hilaire Hilal Hilario Hilel Hippolyte Hisham Hissam Hoel Honore Hubert Hugo Hugues Humbert Hussein Hyacinthe Iacopo Ian Iann Iban Ibban Ibrahime Idris Idriss Idryss Idy Ignace Igor Iker Ikram Ilan Ilann Iliane Iliann Ilkay Illan Illias Ilyes Ilyess Imade Imane Imbert Indy Ioan Iouri Irénée Irfane Irvin Irving Irwin Isa Isaac Isaak Isac Isai Isaia Isaiah Isaie Isak Isam Iscander Ishac Isidore Iskandre Islam Ismaël Ismaila Ismain Israel Issan Issey Ivan Ivann Ivano Iwan Izak Iacopo Ian Iann Iban Ibban Ibrahime Idris Idriss Idryss Idy Ignace Igor Iker Ikram Ilan Ilann Iliane Iliann Ilkay Illan Illias Ilyes Ilyess Imade Imane Imbert Indy Ioan Iouri Irénée Irfane Irvin Irving Irwin Isa Isaac Isaak Isac Isai Isaia Isaiah Isaie Isak Isam Iscander Ishac Isidore Iskandre Islam Ismaël Ismaila Ismain Israel Issan Issey Ivan Ivann Ivano Iwan Izak Izzet Jack Jacky Jacob Jacques Jad Jade Jalal Jalil Jamal Jamel James Jamil Jamy Jan Janis Jaouen Jason Jassim Jawad Jawed Jayden Jaymes Jayson Jean Jean-Antoine Jean-Baptiste Jean-Charles Jean-Christophe Jean-Claude Jean-Daniel Jean-David Jean-Emmanuel Jean-Eudes Jean-Francois Jean-Gabriel Jean-Jacques Jean-Louis Jean-Loup Jean-Luc Jean-Marc Jean-Marie Jean-Michel Jean-Noel Jean-Pascal Jean-Paul Jean-Philippe Jean-Pierre Jean-Sebastien Jean-Yves Jeff Jefferson Jeffrey Jéhan Jérémy Jérôme Jerry Jeson Jesse Jessim Jessy Jibril Jim Jimmy Jimy Joachim Joackim Joakim Joan Joann Joao Joaquim Jocelin Jocelyn Joe Joel Joey Joffrey Johan Johann John Johnny Jolan Jon Jonah Jonas Jonathan Joran Jordan Jordane Jordi Jordy Jorian Jorick Joris Jorys José Joseph Joshua Joss Josse Josselin Jossua Josua Josué Jovany Joyce Juan Jude Judicaël Judikaël Jule Julen Jules Julian Juliano Julien Julio Jullian Junior Justin";
    }
    
    /**
     * Retourne une tableau contenant un nombre aléatoire de nombre compris entre 0 et $max
     */
    public function getRandomNumbers($max)
    {
        $array = array();
        $nb = rand (0,$max-1);
        
        $array[] = rand(0, $max-1);
        for($i=1; $i <= $nb; $i++){
            $indice;
            do {$indice = rand(0, $max-1);}
            while(in_array($indice, $array));
            $array[] = $indice;
        }
        
        return $array;
    }
    
    /**
     * Retourne un sous-tableau mélangé contenant un nombre $nbElem d'élément
     * (lorsque $nbElem = 0, le sous-tableau renvoyé contient un nombre aléatoire d'élément)
     * 
     * @param array $array   Tableau qui contient tous les éléments
     * @param int   $nbElem  Nombre d'élément que doit contenir le sous-tableau
     * 
     */
    public function getRandomSubArray($array, $nbElem = 0) {
        $nbMaxElem = count($array);
        $nbElem = ($nbElem == 0 || $nbElem > $nbMaxElem ) ? $nbMaxElem : $nbElem;
        
        $indices = $this->getRandomNumbers($nbElem);
        
        $result = array();
        foreach ($indices as $i) {
            $result [] = $array[$i];
        }
        return $result;
    }
    
    public function parse_csv($file, $head = false, $sep = ';')
    {
        $handle = fopen($file, 'r');

        if(false === $handle)
        {
            return false;
        }

        if(true === $head)
        {
            $header = fgetcsv($handle, 0, $sep);

            if(false === $header)
            {
                return false;
            }
        }

        while($row = fgetcsv($handle, 0, $sep))
        {
            if(false === $row)
            {
                return false;
            }

            if(true === $head)
            {
                $data[] = $row + array_combine($header, $row);
            }
            else
            {
                $data[] = $row;
            }
        }    

        if(false === empty($data))
        {
            return $data;
        }

        return false;
    }
    
}
