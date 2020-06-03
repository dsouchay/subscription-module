<?php

namespace Drupal\mailing_subscriptions\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\mailing_subscriptions\Services\MailingSubscriptionToolbox;
use Exception;


/**
 * Provides a form for  MailingSubscription.
 *
 * @internal
 */
class MailingSubscriptionForm extends FormBase {


  protected $step = 1;
  protected $session;
  protected $arguments;

  /**
   * Constructs a new MailingSubscriptionForm.
   */

  public function __construct(MailingSubscriptionToolbox $arguments)
  {

    $this->arguments = $arguments;
    $this->session = \Drupal::request()->getSession();

  }


  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('mailing.toolbox')

    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'mailing_subscription';
  }

  /**
   * {@inheritdoc}
   */



  public function buildForm(array $form, FormStateInterface $form_state)
  {

    $form['#prefix'] = '<div id="ajax_form_multistep_form">';
    $form['#suffix'] = '</div>';
    if ($this->step == 1) {

      $form['message-title'] = [
        '#markup' => '<h3>' . $this->t('Always be up to date and receive exciting articles from') . '</h3>',
      ];

      $form['options'] = [
        '#type' => 'checkboxes',
        '#options' => ['og'=>'General', 'hb' => 'Health and Beauty', 'sl' => 'Sports and Leisure', 'gg' => 'Garden Greens'],
        '#attributes' => [
          'class' => [
            'form-control',
            'filter'
          ]
        ],
        '#default_value' => ['og']

      ];
    }

    if ($this->step == 2) {

      $form['message-title'] = [
        '#markup' => '<h2>' . $this->t('Please enter your contact details below:') . '</h2>',
      ];

      $form['email'] = [
        '#type' => 'email',
        '#title' => $this->t('Email address'),
        '#placeholder' => $this->t('Email address'),
        '#attributes' => array('class' => array('mail-first-step')),
        '#required' => TRUE,
      ];
      $form['subscribe'] = [
        '#type' => 'checkbox',
        '#title' => $this->t('Subscribe to newsletter'),
      ];
      $form['agree'] = [
        '#markup' => '<p class="agree">' . $this->t(
          ' By signing up you agree to the <a href="@terms">Terms and Conditions</a> and <a href="@policy">Privacy Policy</a>',
          array('@terms' => '/terms-and-conditions', '@policy' => '/privacy-policy')
        ) . '</p>',
      ];
    }

    if ($this->step == 3) {
      $this->session->remove('newsletter.options');
      $form['message-title'] = [
        '#markup' => '<h2>' . $this->t('Thank you') . ' :) !!!</h2>',
      ];
    }

    if ($this->step == 1) {
      $form['buttons']['forward'] = array(
        '#type' => 'submit',
        '#value' => t('Subscribe now'),
        '#prefix' => '<div class="step1-button">',
        '#suffix' => '</div>',
        '#ajax' => array(
          'wrapper' => 'ajax_form_multistep_form',
          'callback' => '::ajax_form_multistep_form_ajax_callback',
          'event' => 'click',
        ),
      );
    }
    if ($this->step == 2) {
      $form['buttons']['forward'] = array(
        '#type' => 'submit',
        '#value' => t('Submit'),
        '#states' => [
          'visible' => [
            ':input[name="subscribe"]' =>array('checked' => TRUE),
          ],
        ],
        '#ajax' => array(
          'wrapper' => 'ajax_form_multistep_form',
          'callback' => '::ajax_form_multistep_form_ajax_callback',
          'event' => 'click',
        ),
      );
    }

    return $form;
  }

  public function validateForm(array &$form, FormStateInterface $form_state)
  {
    return parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    $values = $form_state->getValues();
    switch ($this->step){
      case 1:
        $this->session->set('newsletter.options', $values['options']);
        break;
      case 2:
        $result['mail'] = $values['email'];
        $result['options'] = $this->session->get('newsletter.options');
        $result['ip'] = \Drupal::request()->getClientIp();
        $this->arguments->registerSubscription($result);
        break;
    }

    $this->step++;
    $form_state->setRebuild();
  }

  public function ajax_form_multistep_form_ajax_callback(array &$form, FormStateInterface $form_state)
  {
    return $form;
  }

}
