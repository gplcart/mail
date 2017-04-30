<?php

/**
 * @package Mail
 * @author Iurii Makukh
 * @copyright Copyright (c) 2017, Iurii Makukh
 * @license https://www.gnu.org/licenses/gpl-3.0.en.html GPL-3.0+
 */

namespace gplcart\modules\mail;

use gplcart\core\Module,
    gplcart\core\Library;

/**
 * Main class for Mail module
 */
class Mail extends Module
{

    /**
     * Library class instance
     * @var \gplcart\core\Library $library
     */
    protected $library;

    /**
     * PHPMailer class instance
     * @var \PHPMailer $mailer
     */
    protected $mailer;

    /**
     * @param Library $library
     */
    public function __construct(Library $library)
    {
        parent::__construct();

        $this->library = $library;
    }

    /**
     * Implements hook "library.list"
     * @param array $libraries
     */
    public function hookLibraryList(array &$libraries)
    {
        $libraries['phpmailer'] = array(
            'name' => 'PHP Mail',
            'description' => 'The classic email sending library for PHP',
            'url' => 'https://github.com/PHPMailer/PHPMailer',
            'download' => 'https://github.com/PHPMailer/PHPMailer/archive/v5.2.23.zip',
            'type' => 'php',
            'version_source' => array(
                'lines' => 100,
                'pattern' => '/.*\\$Version.*(\\d+\\.+\\d+\\.+\\d+)/',
                'file' => 'vendor/phpmailer/phpmailer/class.phpmailer.php'
            ),
            'module' => 'mail',
            'files' => array(
                'vendor/autoload.php'
            )
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
        $settings = $this->config->module('mail');

        // Check if the module enabled AND result is null, i.e not caught by another module
        if (!empty($settings['status']) && $result === null) {
            $sent = $this->send($to, $subject, $message, $options, $settings);
            if ($sent === true) {
                // On success override $result argument to mark the massage was sent
                // Another modules implementing this hook won't send this message again
                $result = true;
            }
        }
    }

    /**
     * Set PHPMailer instance
     * @return \PHPMailer
     * @throws \InvalidArgumentException
     */
    protected function setMailerInstance()
    {
        $this->library->load('phpmailer');

        if (class_exists('PHPMailer')) {
            return $this->mailer = new \PHPMailer;
        }

        throw new \InvalidArgumentException('Class PHPMailer not found');
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
            $this->setMailerInstance();
        } catch (\InvalidArgumentException $ex) {
            return $ex->getMessage();
        }

        $this->mailer->isSMTP();
        $this->mailer->isHTML(!empty($options['html']));

        $this->mailer->Body = $message;
        $this->mailer->Subject = $subject;

        if (!empty($options['html'])) {
            $this->mailer->AltBody = strip_tags($message);
        }

        $this->mailer->Port = $settings['port'];
        $this->mailer->setFrom($options['from']);
        $this->mailer->Username = $settings['user'];
        $this->mailer->Password = $settings['password'];
        $this->mailer->SMTPSecure = $settings['secure'];
        $this->mailer->SMTPAuth = !empty($settings['auth']);
        $this->mailer->Host = implode(';', $settings['host']);

        foreach ($to as $address) {
            settype($address, 'array');
            call_user_func_array(array($this->mailer, 'addAddress'), $address);
        }

        if (!empty($options['attachment'])) {
            foreach ($options['attachment'] as $attachment) {
                settype($attachment, 'array');
                call_user_func_array(array($this->mailer, 'addAttachment'), $attachment);
            }
        }

        if ($this->mailer->send()) {
            return true;
        }

        return (string) $this->mailer->ErrorInfo;
    }

    /**
     * Implements hook "module.enable.after"
     */
    public function hookModuleEnableAfter()
    {
        $this->library->clearCache();
    }

    /**
     * Implements hook "module.disable.after"
     */
    public function hookModuleDisableAfter()
    {
        $this->library->clearCache();
    }

    /**
     * Implements hook "module.install.after"
     */
    public function hookModuleInstallAfter()
    {
        $this->library->clearCache();
    }

    /**
     * Implements hook "module.uninstall.after"
     */
    public function hookModuleUninstallAfter()
    {
        $this->library->clearCache();
    }

}
