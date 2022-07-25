<?php

    namespace App\Form\DTO;

    class EventDTO
    {
        /**
         * @return mixed
         */
        public function getCampus()
        {
            return $this->campus;
        }

        /**
         * @param mixed $campus
         */
        public function setCampus($campus): void
        {
            $this->campus = $campus;
        }

        /**
         * @return mixed
         */
        public function getName()
        {
            return $this->name;
        }

        /**
         * @param mixed $name
         */
        public function setName($name): void
        {
            $this->name = $name;
        }

        /**
         * @return mixed
         */
        public function getFromDate()
        {
            return $this->fromDate;
        }

        /**
         * @param mixed $fromDate
         */
        public function setFromDate($fromDate): void
        {
            $this->fromDate = $fromDate;
        }

        /**
         * @return mixed
         */
        public function getToDate()
        {
            return $this->toDate;
        }

        /**
         * @param mixed $toDate
         */
        public function setToDate($toDate): void
        {
            $this->toDate = $toDate;
        }

        /**
         * @return mixed
         */
        public function getPastEvent()
        {
            return $this->pastEvent;
        }

        /**
         * @param mixed $pastEvent
         */
        public function setPastEvent($pastEvent): void
        {
            $this->pastEvent = $pastEvent;
        }

        /**
         * @return mixed
         */
        public function getIsTheOrganiser()
        {
            return $this->isTheOrganiser;
        }

        /**
         * @param mixed $isTheOrganiser
         */
        public function setIsTheOrganiser($isTheOrganiser): void
        {
            $this->isTheOrganiser = $isTheOrganiser;
        }

        /**
         * @return mixed
         */
        public function getEventAttendenceTrue()
        {
            return $this->eventAttendenceTrue;
        }

        /**
         * @param mixed $eventAttendenceTrue
         */
        public function setEventAttendenceTrue($eventAttendenceTrue): void
        {
            $this->eventAttendenceTrue = $eventAttendenceTrue;
        }

        /**
         * @return mixed
         */
        public function getEventAttendenceFalse()
        {
            return $this->eventAttendenceFalse;
        }

        /**
         * @param mixed $eventAttendenceFalse
         */
        public function setEventAttendenceFalse($eventAttendenceFalse): void
        {
            $this->eventAttendenceFalse = $eventAttendenceFalse;
        }

        public $campus;
        public $name;
        public $fromDate;
        public $toDate;
        public $pastEvent;
        public $isTheOrganiser;
        public $eventAttendenceTrue;
        public $eventAttendenceFalse;

    }


    // Pas besoin de typer car Symfony est un génie

    //getters et setters