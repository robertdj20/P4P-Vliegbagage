<?php

require_once("includeAll.php");

class user
{
    public $id;
    public $userName;

    private static function UsernameExists($username)
    {
        $userExist = DbHandler::Query("SELECT username FROM users WHERE username = '$username'");
        return $userExist;
    }
    
    public static function createUser($username,$userPassword)
    {   
        $userId;
        $check = UserNameExists($username);
        if(check != true)
        {
            DbHandler::Query("INSERT INTO user(username,userPassword) VALUES (:Name:), (:userPassword:)", 
            array("Name" => $username, "userPassword" => $userPassword));
        }                                
    }
    public static function deleteUser($userid)
    {        
        $userId;
        DbHandler::NonQuery("DELETE FROM user WHERE userid = (:ID:)", array("ID" => $userid));                            
    }
    
    public static function changeUser($userid,$username,$userPassword)
    {
        $check = UserNameExist($username);
        $user =GetUser($userid);
        if(check == false|| $user->userName == $username )
        {
           DbHandler::Query("UPDATE user SET username = (:Name;), userPassword = (:password:) WHERE userid = (:ID)", array("Name" => $username, "ID" => $userid, "password" => $userPassword));
        }
        $userId;                               
    }
    
    //Gebruiker ophalen aan de hand van de id die wordt opgegeven
    public static function GetUser($userid)
    {
        $results = DbHandler::Query("SELECT * FROM user WHERE user_id=:id;", array("id"=>$userid));
        if($results !=null)
        {
            $gebruiker = new user();
            
          $gebruiker->id  = $results[0]["user_id"];
           $gebruiker->userName  = $results[0]["username"];
           return $gebruiker;
        }
        else
        {
            return null;
        }                               
    }
    
    //Alle gebruikers die in de database staan terug geven afhankelijk van de zoekterm en de hoeveelste gebruikers
    public static function GetUsers($searchTerm, $start,$end)
    {
            $results = DbHandler::Query("SELECT * FROM user WHERE username LIKE :search LIMIT :beg, :end;", array(
            "search"=> '%'.$searchTerm.'%', "beg"=>$start,"end"=>$end));
            //als gebruikers leeg is null terug geven
        if($results !=null)
        {
      $gebruikers = array();
            for($i =0;$i<count($results);$i++)
            {
            $gebruiker = new user();
            $gebruiker->id  = $results[$i]["user_id"];
            $gebruiker->userName  = $results[$i]["username"];
            //Gebruiker toevoegen aan array
            $gebruikers[] = $gebruiker;
           }
           return $gebruikers;
        }
        else
        {
            return null;
        }                       
    }
    
    //fucntie om te checken of het een geldige login is. 
    //als dit het geval is dan wordt er een object van gebruiker terug gegeven.
    public static function login($username,$userPassword)
    {
      $results = DbHandler::Query("SELECT user_id, username FROM user WHERE username=:user AND password=:pass;", array(
            "user"=> $username, "pass"=>$userPassword));
            //als er geen gebruiker bestaat met deze combinatie wordt null terug gegeven
        if($results !=null)
        {
            
            $gebruiker = new user();
            
          $gebruiker->id  = $results[0]["user_id"];
           $gebruiker->userName  = $results[0]["username"];
           return $gebruiker;
        }
        else
        {
         //null terug geven   
            return null;
        }
    }
}
?>