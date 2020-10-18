<?php



class Perso
{
  protected $id,
            $nom,
            $atk,
            $def,
            $type_elmt,
            $niveaux,
            $experiences,
            $stars,
            $pv,
            $pvm,
            $camp;
  

  const CEST_MOI = 1; // Constante renvoyée par la méthode `frapper` si on se frappe soit-même.
  const PERSONNAGE_TUE = 2; // Constante renvoyée par la méthode `frapper` si on a tué le personnage en le frappant.
  const PERSONNAGE_FRAPPE = 3; // Constante renvoyée par la méthode `frapper` si on a bien frappé le personnage.
  const PERSONNAGE_ENSORCELE = 4; // Constante renvoyée par la méthode `lancerUnSort` (voir classe Magicien) si on a bien ensorcelé un personnage.
  const PAS_DE_MAGIE = 5; // Constante renvoyée par la méthode `lancerUnSort` (voir classe Magicien) si on veut jeter un sort alors que la magie du magicien est à 0.
  const PERSO_ENDORMI = 6; // Constante renvoyée par la méthode `frapper` si le personnage qui veut frapper est endormi.
  
  const PEUT_PAS_FRAPPER = 7;
  const LEVEL_UP = 8;
  const PERSONNAGE_SOIGNE = 9;

  public function __construct($heros,$list,$camp)
  {
    //$this->hydrate($heros);

    $this->setNom($heros);
    $this->setAtk($list);
    $this->setDef($list);
    $this->setType_elmt($list);
    $this->setNiveaux(1);
    $this->setExperiences(0);
    $this->setStars(3);
    $this->setPvm();
    $this->setPv($this->pvm);
    $this->setCamp($camp);

  }

 // Méthodes Magiques ! 
public function __set($name, $value)
{
  echo "<p>On ne peut pas mettre la valeur \"".$value."\" à l'attribut \"".$name."\" ici.</p>";
}

public function __get($name)
{
  return "<p>On ne peut pas récupérer l'attribut \"".$name."\" ici.</p>";
}

public function __isset($name)
{
  // Avec if (isset($objet->attribut)) on peut vérifier s'il existe ou non
}

public function __call($name, $arguments)
{
  echo "<p>La méthode \"".$name."\" a été appelé mais n'existe pas ou n'est pas disponible.<br />
  Elle possédait le(s) argument(s) suivant(s) : \"".implode($arguments)."\"</p>"; 
}
public function __toString()
{
  return $this->donnees;
}

/*
  public function estEndormi()
  {
    return $this->timeEndormi > time();
  }
 
  public function estFatigue()
  {
      // est fatigéu si le temps d'attente pas passé, ET si il a envoyées 3 ou + de coups
    return $this->timeWait > time() && $this->coups_envoyees >= 2 + $this->niveaux;
  }

  public function updateFatigue()
  {
    if ($this->timeWait < time())
    {     // On réinitialise le nombre de coups_envoyees à chaque fois qu'on a assez attendu
              // Exemple : 2 coups puis on revient le lendemain, alors on a le droit à 3 coups et pas 1 seul
    $this->coups_envoyees = 0;    // Car ça serait ridicule de faire "3 puis repos", et pas "3" alors que les 2 coups c'était hier
    }
  }


  public function frapper(Personnage $perso)
  {
    if ($perso->id == $this->id)
    {
      return self::CEST_MOI;
    }
    
    
    // Si le personnage a trop combattu
    
    if ($this->estFatigue())
    {
      return self::PEUT_PAS_FRAPPER;
    }
    else
    { 

      $this->updateFatigue();

        // Alors on attend 6 heures avant de pouvoir recombattre
      $this->timeWait = time() + 6; // une minute //* 3600;
      $this->coups_envoyees += 1;

      $this->experiences += 10;
      $this->isLevelUp();
    }


    if ($this->estEndormi())
    {
      return self::PERSO_ENDORMI;
    }


    // Si c'est un rôdeur, on l'empoisonne !
    if ($this->type == "rodeur")
    {
      $this->empoisonner($perso);
    }

    // On indique au personnage qu'il doit recevoir des dégâts.
    // Puis on retourne la valeur renvoyée par la méthode : self::PERSONNAGE_TUE ou self::PERSONNAGE_FRAPPE.
    return $perso->recevoirDegats();
  }
  */

  public function hydrate(array $donnees)
  {
    foreach ($donnees as $key => $value)
    {
      $method = 'set'.ucfirst($key);
      
      if (method_exists($this, $method))  // Fait tout d'un coup
      {
        $this->$method($value);
      }
    }
  }
  /*
  public function nomValide()
  {
    return !empty($this->nom);
  }
  
  public function recevoirDegats()
  {
    $this->degats += 5;

    $this->experiences += 4;
    $this->isLevelUp();

    // Si on a 100 de dégâts ou plus, on supprime le personnage de la BDD.
    if ($this->degats >= 100)
    {
      return self::PERSONNAGE_TUE;

    }
    
    // Sinon, on se contente de mettre à jour les dégâts du personnage.
    return self::PERSONNAGE_FRAPPE;
  }
  
  public function reveil()
  {
    $secondes = $this->timeEndormi;
    $secondes -= time();
    
    $heures = floor($secondes / 3600);
    $secondes -= $heures * 3600;
    $minutes = floor($secondes / 60);
    $secondes -= $minutes * 60;
    
    $heures .= $heures <= 1 ? ' heure' : ' heures';
    $minutes .= $minutes <= 1 ? ' minute' : ' minutes';
    $secondes .= $secondes <= 1 ? ' seconde' : ' secondes';
    
    return $heures . ', ' . $minutes . ' et ' . $secondes;
  }

  public function repos()
  {
    $secondes = $this->timeWait;
    $secondes -= time();
    
    $heures = floor($secondes / 3600);
    $secondes -= $heures * 3600;
    $minutes = floor($secondes / 60);
    $secondes -= $minutes * 60;
    
    $heures .= $heures <= 1 ? ' heure' : ' heures';
    $minutes .= $minutes <= 1 ? ' minute' : ' minutes';
    $secondes .= $secondes <= 1 ? ' seconde' : ' secondes';
    
    return $heures . ', ' . $minutes . ' et ' . $secondes;
  }
  */

  public function id()
  {
    return $this->id;
  }
  
  public function nom()
  {
    return $this->nom;
  }
  
  public function atk()
  {
    return $this->atk;
  }
  
  public function def()
  {
    return $this->def;
  }
  
  public function type_elmt()
  {
    return $this->type_elmt;
  }

  public function niveaux()
  {
    return $this->niveaux;
  }
  
  public function experiences()
  {
    return $this->experiences;
  }

  public function stars()
  {
    return $this->stars;
  }
  public function pv()
  {
    return $this->pv;
  }
  public function pvm()
  {
    return $this->pvm;
  }
  public function camp()
  {
    return $this->camp;
  }

  
  // SETTER

  public function setId($id)
  {
    $id = (int) $id;
    
    if ($id > 0)
    {
      $this->id = $id;
    }
  }
  public function setNom($nom)
  {
    if (is_string($nom))
    {
      $this->nom = substr($nom, 0, -4);
    }
  }
  public function setAtk($list)
  {
    $atk = 8 + $list[$this->nom][0] + rand(-1,1) ;
    $this->atk = $atk;
  }
  
  public function setDef($list)
  {
    $def = 8 + $list[$this->nom][1] + rand(-1,1) ;
    $this->def = $def;
  }
  

  public function setType_elmt($list)
  {
    $this->type_elmt = $list[$this->nom][2];
  }
/*
  public function setTimeWait($time)
  {
    $this->timeWait = (int) $time;
  }

  public function setCoups_envoyees($value)
  {
    $this->coups_envoyees = (int) $value;
  }
*/
  public function setNiveaux($value)
  {
    $this->niveaux = (int) $value;
    if ($this->niveaux <= 0) // interdit d'être <= à 0
    {
      $this->niveaux = 1;
    }
  }
  
  public function setExperiences($value)
  {
    $this->experiences = (int) $value;
    if($this->experiences < 0) // interdit d'être < à 0
    {
      $this->experiences = 0;
    }
  }

  public function setStars($value)
  {
    $this->stars = (int) $value;
  }

  public function setPv($value)
  {
    if($value > $this->pvm){
      $this->pv = $this->pvm; }
    else if($value < 0){ $this->pv = 0; }
    else{  $this->pv = (int) $value; }
  }
  public function setPvm()
  {
    $pvm = 10 + $this->stars * 2 + rand(-2,2);
    $this->pvm = $pvm;
  }
  public function setCamp($camp)
  {
    $this->camp = (int) $camp % 2; // Eviter de sortir un camp 2,3,4,...
  }


  public function isLevelUp()
  {
    if ($this->experiences >= 100)
    {
      $this->experiences = $this->experiences % 100; // Modulo
      $this->niveaux += 1;
    }

  }

}