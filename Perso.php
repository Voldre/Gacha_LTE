<?php

/*
Une classe doit toujours avoir ses setters le plus général possible! setPvm($value) { verif... $this->pvm = $value;}

    Même si on pense toujours mettre une valeur précise (ex : $list[$nom][1]), on doit toujours globaliser!
    Parce que le jour où on appelle le setter en dehors de ce cas, on est foutu! Car le setter comprend que des LISTES
    Qui plus est, avec une structure d'index précise.

    Moi je déclare mes objets soit à la mano, soit en SQL, du coup mes setters doivent être global, et l'un trouvera
    sa valeur dans $list, l'autre dans $liste_SQL, c'est tout!
    
Bien penser à récupérer les variables SQL ATK_P, DEF_P et PVM_P, car elles correspondent aux vrais stats.

    L'erreur est de prendre les stats génériques, donc de la table "Cartes_Personnages", mais aucun random() n'y est.
    Autrement dit, sans ça, tous les persos "load" lors de la connexion auront les mêmes stats si même nom.
    Ex : Zerito 1 et Zerito 2 auront les mêmes stats lors de la conenxion, alors qu'en BDD elles sont différentes.
    Car Cartes_des_Joueurs liste les stats de chaque perso, et Cartes_Personnages liste les stats génériques des persos.

*/

class Perso
{
  protected $nom,
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

  public function __construct($heros,$list,$camp, $list_SQL = "") // Pas obligé de préciser le nombre d'étoiles
  {                                                        // Pas obliger de passer par une requête SQL
    //$this->hydrate($heros);

    if($list_SQL != ""){  // Chargement du Perso déjà existant dans la BDD
      
      $this->setNom($list_SQL['NOM']); 
      $this->setStars($list_SQL['STARS']);
      $this->setAtk($list_SQL['ATK_P']); // _P car spécifique au perso enregistré, on charge ses stats à lui
      $this->setDef($list_SQL['DEF_P']); // _P car spécifique au perso enregistré, on charge ses stats à lui
      $this->setType_elmt($list_SQL['ELMT']);
      $this->setPvm($list_SQL['PVM_P']); // _P car spécifique au perso enregistré, on charge ses stats à lui

    }
    else{     // Adapté pour supporter le nouveau format de $list, $liste_4_stars, en mode SQL : Création de perso
      $this->setNom($heros);
      $this->setStars($list[$heros]["STARS"]);
      $this->setAtk($list[$heros]["ATK"] + rand(-1,1) ); // Double tableau [nom][stats] 
      $this->setDef($list[$heros]["DEF"] + rand(-1,1) ); // car on a dit: tous les premiers array = le nom
      $this->setType_elmt($list[$heros]["ELMT"]);        // Direction Listes_Persos.php pour plus d'info!
      $this->setPvm(10 + $this->stars * 2 + rand(-2,2) );                 // Section : Objects lists
    }
    
      // Commun peu importe la méthode
    //$this->setNiveaux(1);
    //$this->setExperiences(0);
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
  
  // ACCESSEUR

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

  public function setNom($nom)
  {
    if (is_string($nom))
    {
      //$this->nom = substr($nom, 0, -4); Plus besoin !
      $this->nom = $nom;
    }
  }
  public function setAtk($value)
  {
    $this->atk = (int) $value;
  }
  
  public function setDef($value)
  {
    $this->def = (int) $value;
  }
  

  public function setType_elmt($value)
  {
    $this->type_elmt = (string) $value;
  }
  
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
  public function setPvm($value)
  {
    $this->pvm = (int) $value;
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