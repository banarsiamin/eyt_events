<?php
/**
 * ICS.php
 * =======
 * Use this class to create an .ics file.
 *
 * Usage
 * -----
 * Basic usage - generate ics file contents (see below for available properties):
 *   $ics = new ICS($props);
 *   $ics_file_contents = $ics->to_string();
 *
 * Setting properties after instantiation
 *   $ics = new ICS();
 *   $ics->set('summary', 'My awesome event');
 *
 * You can also set multiple properties at the same time by using an array:
 *   $ics->set(array(
 *     'dtstart' => 'now + 30 minutes',
 *     'dtend' => 'now + 1 hour'
 *   ));
 *
 * Available properties
 * --------------------
 * description
 *   String description of the event.
 * dtend
 *   A date/time stamp designating the end of the event. You can use either a
 *   DateTime object or a PHP datetime format string (e.g. "now + 1 hour").
 * dtstart
 *   A date/time stamp designating the start of the event. You can use either a
 *   DateTime object or a PHP datetime format string (e.g. "now + 1 hour").
 * location
 *   String address or description of the location of the event.
 * summary
 *   String short summary of the event - usually used as the title.
 * url
 *   A url to attach to the the event. Make sure to add the protocol (http://
 *   or https://).
 */
class WP_ICS{
    const DT_FORMAT = 'Ymd\THis';
    protected $properties = array();
    private $available_properties = array(
      'description',
      'dtend',
      'dtstart',
      'location',
      'summary',
      'url'
    );
    public function __construct($props) {
      $this->set($props);
    }
    public function set($key, $val = false) {
      if (is_array($key)) {
        foreach ($key as $k => $v) {
          $this->set($k, $v);
        }
      } else {
        if (in_array($key, $this->available_properties)) {
          $this->properties[$key] = $this->sanitize_val($val, $key);
        }
      }
    }
    public function to_string() {
      $rows = $this->build_props();
      return implode("\r\n", $rows);
    }
    private function build_props() {
      // Build ICS properties - add header
      $ics_props = array(
        'BEGIN:VCALENDAR',
        'VERSION:2.0',
        'PRODID:-//hacksw/handcal//NONSGML v1.0//EN',
        'CALSCALE:GREGORIAN',
        'METHOD:PUBLISH',
        'BEGIN:VEVENT'
      );
      // $ics_data = "BEGIN:VCALENDAR\nVERSION:2.0\nMETHOD:PUBLISH\nBEGIN:VEVENT\nDTSTART:".date("Ymd\THis\Z",strtotime($start))."\nDTEND:".date("Ymd\THis\Z",strtotime($end))."\nLOCATION:".$location."\nTRANSP: OPAQUE\nSEQUENCE:0\nUID:\nDTSTAMP:".date("Ymd\THis\Z")."\nSUMMARY:".$name."\nDESCRIPTION:".$description."\nPRIORITY:1\nCLASS:PUBLIC\nBEGIN:VALARM\nTRIGGER:-PT10080M\nACTION:DISPLAY\nDESCRIPTION:Reminder\nEND:VALARM\nEND:VEVENT\nEND:VCALENDAR\n";
      // Build ICS properties - add header
      $props = array();
      foreach($this->properties as $k => $v) {
        $props[strtoupper($k . ($k === 'url' ? ';VALUE=URI' : ''))] = $v;
      }
      // Set some default values
      $props['DTSTAMP'] = $this->format_timestamp('now');
      $props['UID'] = uniqid();
      // Append properties
      foreach ($props as $k => $v) {
        $ics_props[] = "$k:$v";
      }
      // Build ICS properties - add footer
      $ics_props[] = 'END:VEVENT';
      $ics_props[] = 'END:VCALENDAR';
      return $ics_props;
    }
    private function sanitize_val($val, $key = false) {
      switch($key) {
        case 'dtend':
        case 'dtstamp':
        case 'dtstart':
          $val = $this->format_timestamp($val);
          break;
        default:
          $val = $this->escape_string($val);
      }
      return $val;
    }
    private function format_timestamp($timestamp) {
      $dt = new DateTime($timestamp);
      return $dt->format(self::DT_FORMAT);
    }
    private function escape_string($str) {
      return preg_replace('/([\,;])/','\\\$1', $str);
    }
  }



  class ICS {
    var $data;
    var $name;
    var $booking_id;
    function ICS($myDT) {
		$description = isset($myDT['description'])?$myDT['description']:'This new event book';
		$location = isset($myDT['location'])?$myDT['location']:'All';
		$name = isset($myDT['name'])?$myDT['name']:'Pixel';
		$date = date('Y-m-d');
		$start = isset($myDT['start'])?$myDT['start']:date('Y-m-d', strtotime($date.'+5 days'));
		$date_end = date('Y-m-d', strtotime($start.'+1 days'));
		$end = $date_end;//isset($myDT['end'])?$myDT['end']:'';
		$booking_id = isset($myDT['booking_id'])?$myDT['booking_id']:time();
        $this->name = $name;
        $this->booking_id = $booking_id;
        $this->data = "BEGIN:VCALENDAR\nVERSION:2.0\nMETHOD:PUBLISH\nBEGIN:VEVENT\nDTSTART:".date("Ymd\THis\Z",strtotime($start))."\nDTEND:".date("Ymd\THis\Z",strtotime($end))."\nLOCATION:".$location."\nTRANSP: OPAQUE\nSEQUENCE:0\nUID:\nDTSTAMP:".date("Ymd\THis\Z")."\nSUMMARY:".$name."\nDESCRIPTION:".$description."\nPRIORITY:1\nCLASS:PUBLIC\nBEGIN:VALARM\nTRIGGER:-PT10080M\nACTION:DISPLAY\nDESCRIPTION:Reminder\nEND:VALARM\nEND:VEVENT\nEND:VCALENDAR\n";
    }
    function save() {
        file_put_contents($this->name.".ics",$this->data);
    }
    function show() {
        header("Content-type:text/calendar");
        header('Content-Disposition: attachment; filename="'.$this->name.'.ics"');
        Header('Content-Length: '.strlen($this->data));
        Header('Connection: close');
        echo $this->data;
    }
}