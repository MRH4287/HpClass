<?php
/*
Die Klasse, von denen alle Plugins erben

*/
abstract class Plugin
{

// Der Name des Plugins
public $name;
// Die Versiond des Plugins
public $version = "";
// Der Autor des Plugins
public $autor = "";
// Die Homepage des Autors
public $homepage = "";
// Notizen des Autors (z.B. Changelog)
public $notes = "";
// Ist dieses Plugin von Anfang an Aktiviert?
public $isEnabled = false;
// Lokale Refferent auf HpClass
protected $hp;

// Lokale Refferenz auf den PluginLoader
protected $loader;

// Soll der Zustand dieses Plugins gesperrt werden?
// Sollte nur bei Systemkritischen Plugins auf true stehen!
public $lock = false;

function __construct($hp, $loader)
{
$this->hp       = $hp;
$this->loader   = $loader;

}


function OnEnable()
{
// Leerer Funktionsrumpf
// Warnen, sollte diese Funktion nicht überschrieben worden sein:
echo "<b>Warning:</b> Die Funktion 'OnEnable' wurde im Plugin '".$this->name."' nicht überschrieben! <br />";


}


function OnLoad()
{
// Leerer Funktionsrumpf
// Warnen, sollte diese Funktion nicht überschrieben worden sein:
echo "<b>Warning:</b> Die Funktion 'OnLoad' wurde im Plugin '".$this->name."' nicht überschrieben! <br />";


}


}
?>