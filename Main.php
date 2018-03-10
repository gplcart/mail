<?php

/**
 * @package Mail
 * @author Iurii Makukh
 * @copyright Copyright (c) 2017, Iurii Makukh
 * @license https://www.gnu.org/licenses/gpl-3.0.en.html GPL-3.0+
 */

namespace gplcart\modules\mail;

use Exception;
use gplcart\core\Library;
use gplcart\core\Module;
use LogicException;
use PHPMailer;

/**
 * Main class for Mail module
 */
class Main
{

    /**
     * Module class instance
     * @var \gplcart\core\Module $module
     */
    protected $module;

    /**
     * Library class instance
     * @var \gplcart\core\Library $library
     */
    protected $library;

    /**
     * @param Module $module
     * @param Library $library
     */
    public function __construct(Module $module, Library $library)
    {
        $this->module = $module;
        $this->library = $library;
    }

    /**
     * Implements hook "library.list"
     * @param array $libraries
     */
    public function hookLibraryList(array &$libraries)
    {
        $libraries['phpmailer'] = array(
            'name' => 'PHP Mail', // @text
            'description' => 'The classic email sending library for PHP', // @text
            'url' => 'https://github.com/PHPMailer/PHPMailer',
            'download' => 'https://github.com/PHPMailer/PHPMailer/archive/v5.2.23.zip',
            'type' => 'php',
            'vendor' => 'phpmailer/phpmailer',
            'version' => '5.2.23',
            'module' => 'mail'
        );
    }

    /**
     * Implements hook "route.list"
     * @param array $routes
     */
    public function hookRouteList(array &$routes)
    {
        $routes['admin/module/settings/mail'] = array(
            'access' => 'module_edit',
            'handlers' => array(
                'controller' => array('gplcart\\modules\\mail\\controllers\\Settings', 'editSettings')
            )
        );
    }

    /**
     * Implements hook "mail.send"
     * @param array $to
     * @param string $subject
     * @param string $message
     * @param array $options
     * @param mixed $result
     */
    public function hookMailSend($to, $subject, $message, $options, &$result)
    {
        $this->setMailer($to, $subject, $message, $options, $result);
    }

    /**
     * Send an E-mail
     * @param array $to
     * @param string $subject
     * @param string $message
     * @param array $options
     * @param array $settings
     * @return boolean|string
     */
    public function send($to, $subject, $message, $options, $settings)
    {
        try {
            $mailer = $this->getMailer();
        } catch (Exception $ex) {
            return $ex->getMessage();
        }

        $mailer->isSMTP();
        $mailer->isHTML(!empty($options['html']));

        $mailer->Body = $message;
        $mailer->Subject = $subject;

        if (!empty($options['html'])) {
            $mailer->AltBody = strip_tags($message);
        }

        $mailer->Port = $settings['port'];
        $mailer->setFrom($options['from']);
        $mailer->Username = $settings['user'];
        $mailer->Password = $settings['password'];
        $mailer->SMTPSecure = $settings['secure'];
        $mailer->SMTPAuth = !empty($settings['auth']);
        $mailer->Host = implode(';', $settings['host']);

        foreach ($to as $address) {
            settype($address, 'array');
            call_user_func_array(array($mailer, 'addAddress'), $address);
        }

        if (!empty($options['attachment'])) {
            foreach ($options['attachment'] as $attachment) {
                settype($attachment, 'array');
                call_user_func_array(array($mailer, 'addAttachment'), $attachment);
            }
        }

        if ($mailer->send()) {
            return true;
        }

        return (string) $mailer->ErrorInfo;
    }

    /**
     * Returns PHPMailer instance
     * @return PHPMailer
     * @throws LogicException
     */
    public function getMailer()
    {
        $this->library->load('phpmailer');

        if (class_exists('PHPMailer')) {
            return new PHPMailer;
        }

        throw new LogicException('Class \PHPMailer not found');
    }

    /**
     * @param array $to
     * @param string $subject
     * @param string $message
     * @param array $options
     * @param mixed $result
     */
    protected function setMailer($to, $subject, $message, $options, &$result)
    {
        $settings = $this->module->getSettings('mail');

        if (!empty($settings['status']) && $result === null) {
            $sent = $this->send($to, $subject, $message, $options, $settings);
            if ($sent === true) {
                $result = true;
            }
        }
    }

}
