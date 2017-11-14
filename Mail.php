<?php

/**
 * @package Mail
 * @author Iurii Makukh
 * @copyright Copyright (c) 2017, Iurii Makukh
 * @license https://www.gnu.org/licenses/gpl-3.0.en.html GPL-3.0+
 */

namespace gplcart\modules\mail;

use gplcart\core\Module,
    gplcart\core\Config;

/**
 * Main class for Mail module
 */
class Mail extends Module
{

    /**
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        parent::__construct($config);
    }

    /**
     * Implements hook "library.list"
     * @param array $libraries
     */
    public function hookLibraryList(array &$libraries)
    {
        $libraries['phpmailer'] = array(
            'name' => /* @text */'PHP Mail',
            'description' => /* @text */'The classic email sending library for PHP',
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
        $this->setMailer($to, $subject, $message, $options, $result);
    }

    /**
     * Implements hook "module.enable.after"
     */
    public function hookModuleEnableAfter()
    {
        $this->getLibrary()->clearCache();
    }

    /**
     * Implements hook "module.disable.after"
     */
    public function hookModuleDisableAfter()
    {
        $this->getLibrary()->clearCache();
    }

    /**
     * Implements hook "module.install.after"
     */
    public function hookModuleInstallAfter()
    {
        $this->getLibrary()->clearCache();
    }

    /**
     * Implements hook "module.uninstall.after"
     */
    public function hookModuleUninstallAfter()
    {
        $this->getLibrary()->clearCache();
    }

    /**
     * Returns PHPMailer instance
     * @return \PHPMailer
     * @throws \InvalidArgumentException
     */
    public function getMailerInstance()
    {
        $this->getLibrary()->load('phpmailer');

        if (class_exists('PHPMailer')) {
            return new \PHPMailer;
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
            $mailer = $this->getMailerInstance();
        } catch (\InvalidArgumentException $ex) {
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
     * @param array $to
     * @param string $subject
     * @param string $message
     * @param array $options
     * @param mixed $result
     */
    protected function setMailer($to, $subject, $message, $options, &$result)
    {
        $settings = $this->config->getFromModule('mail');
        if (!empty($settings['status']) && $result === null) {
            $sent = $this->send($to, $subject, $message, $options, $settings);
            if ($sent === true) {
                $result = true;
            }
        }
    }

}
