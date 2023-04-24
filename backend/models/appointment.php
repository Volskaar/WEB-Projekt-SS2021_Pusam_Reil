<?php
class Appointment {
    public $id;
    public $title;
    public $place;
    public $date;
    public $description;
    public $duration;
    public $options;
    public $active;

    function __construct($id, $title, $place, $date, $description, $duration, $options, $active) {
        $this->id = $id;
        $this->title = $title;
        $this->place = $place;
        $this->date = $date;
        $this->description = $description;
        $this->duration=$duration;
        $this->options=$options;
        $this->active = $active;
    }
}
?>