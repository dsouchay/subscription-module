<?php
namespace Drupal\mailing_subscriptions\Services;

use Drupal\Core\Database\Connection;



class MailingSubscriptionToolbox
{
     protected   $database;

  public function __construct(){
        $this->database = \Drupal::database();
  }


public function registerSubscription($values){
    try {
        $result =  $this->database->insert('mailing_subscriptions')->fields([
            'mail' => $values['mail'],
            'ip' => $values['ip'],
            'optionG' => $values['options']['og'] ? 1 : 0,
            'optionHB' => $values['options']['hb'] ? 1 : 0,
            'optionSL' => $values['options']['sl'] ? 1 : 0,
            'optionGG' => $values['options']['gg'] ? 1 : 0,
            'RequestDateTime' => date("Y-m-d H:i:s"),
        ])->execute();

        return true;
    } catch (Exception $e) {
        \Drupal::logger('mailing_subscriptions')->error($e->getMessage() . " : Error Durante de subscripción.");
        return false;
    }
}


    public function getSubscriptions()
    {
          $query = $this->database->select('mailing_subscriptions', 'm')
        ->fields('m')->execute()->fetchAll();
        // $query = $this->database->query(' select * from {mailing_subscriptions}')->fetchAssoc();
        $result=[];
        foreach ($query as $key=>$item) {
             $result[$key]= json_decode(json_encode($item), true);

        }
       return $result;
    }

}

