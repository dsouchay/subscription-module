<?php namespace Drupal\mailing_subscriptions\Controller;


use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Exception;
use Drupal\mailing_subscriptions\Services\MailingSubscriptionToolbox;



/**
 * Provides route responses for search indexes.
 */
class MailingSubscriptionController extends ControllerBase {

    protected $arguments;

    /**
     * {@inheritdoc}
     */

    public function __construct(MailingSubscriptionToolbox $arguments)
    {

        $this->arguments = $arguments;
    }

    public static function create(ContainerInterface $container) {
        /** @var static $controller */
        return new static(
            $container->get('mailing.toolbox')

        );

    }

    public function content(){
        $list = $this->arguments->getSubscriptions();
        $build = array(
            '#theme' => 'block__list_subscriptions',
            'variables' => array('content' => $list),
        );
        $build['#cache']['max-age'] = 0;

        return $build;
    }


}


