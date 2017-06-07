<?php
/**
* 2007-2017 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2017 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

class Pagoefectivo extends PaymentModule
{
    protected $config_form = false;

    public function __construct()
    {
        $this->name = 'pagoefectivo';
        $this->tab = 'payments_gateways';
        $this->version = '1.4.0';
        $this->author = 'Carlos Loyola';
        $this->need_instance = 1;

        /**
         * Set $this->bootstrap to true if your module is compliant with bootstrap (PrestaShop 1.6)
         */
        $this->bootstrap = true;
        $this->currencies = true;
        $this->currencies_mode = 'checkbox';

        parent::__construct();

        $this->displayName = $this->l('PagoEfectivo');
        $this->description = $this->l('Secure internet transactions in Perú.');
        $this->confirmUninstall = $this->l('Are you sure you want to uninstall the PagoEfectivo module?');

        $this->limited_countries = array('PE');
        $this->limited_currencies = array('PEN','USD');
        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);

        if (!extension_loaded('openssl')) {
            $this->warning = $this->l("You must enable the openssl extension in your php.ini file.");
        }
        if (!extension_loaded('soap')) {
            $this->warning = $this->l("You must enable the soap extension in your php.ini file.");
        }
    }

    /**
     * Don't forget to create update methods if needed:
     * http://doc.prestashop.com/display/PS16/Enabling+the+Auto-Update
     */
    public function install()
    {
        // Validate CURL extension
        if (extension_loaded('curl') == false)
        {
            $this->_errors[] = $this->l('You have to enable the cURL extension on your server to install this module');
            return false;
        }

        // Validate limited countries
        $iso_code = Country::getIsoById(Configuration::get('PS_COUNTRY_DEFAULT'));
        if (in_array($iso_code, $this->limited_countries) == false)
        {
            $this->_errors[] = $this->l('This module is not available in your country');
            return false;
        }

        // Install default
        if (!parent::install()) {
            return false;
        }

        // Registration hook
        if (!$this->registrationHook()) {
            return false;
        }

        // Registration order status
        if (!$this->installOrderState()) {
            return false;
        }

        //Configuration::updateValue('PAGOEFECTIVO_LIVE_MODE', false);

    }

    /**
     * [registrationHook description]
     * @return [type] [description]
     */
    private function registrationHook()
    {
        if (!$this->registerHook('paymentOptions')
            || !$this->registerHook('paymentReturn')
            || !$this->registerHook('displayOrderConfirmation')
            || !$this->registerHook('displayAdminOrder')
            || !$this->registerHook('actionOrderStatusPostUpdate')
            || !$this->registerHook('actionValidateOrder')
            || !$this->registerHook('actionOrderStatusUpdate')
        ) {
            return false;
        }
        return true;
    }

    public function uninstall()
    {
        //Configuration::deleteByName('PAGOEFECTIVO_LIVE_MODE');

        // Uninstall default
        if (!parent::uninstall()
            || !Configuration::deleteByName('PAGOEFECTIVO_OS_PENDING')
            || !Configuration::deleteByName('PAGOEFECTIVO_OS_EXPIRED')
            || !Configuration::deleteByName('PAGOEFECTIVO_OS_REJECTED')) {
            return false;
        }

        return true;
    }

    /**
     * Load the configuration form
     */
    public function getContent()
    {
        /**
         * If values have been submitted in the form, process.
         */
        if (((bool)Tools::isSubmit('submitPagoefectivoModule')) == true) {
            $this->postProcess();
        }

        $this->context->smarty->assign('module_dir', $this->_path);

        $output = $this->context->smarty->fetch($this->local_path.'views/templates/admin/configure.tpl');

        return $output.$this->renderForm();
    }

    /**
     * Create the form that will be displayed in the configuration of your module.
     */
    protected function renderForm()
    {
        $helper = new HelperForm();

        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitPagoefectivoModule';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            .'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFormValues(), /* Add values for your inputs */
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm(array($this->getConfigForm()));
    }

    /**
     * Create the structure of your form.
     */
    protected function getConfigForm()
    {
        return array(
            'form' => array(
                'legend' => array(
                'title' => $this->l('Settings'),
                'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Live mode'),
                        'name' => 'PAGOEFECTIVO_LIVE_MODE',
                        'is_bool' => true,
                        'desc' => $this->l('Use this module in live mode'),
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                    array(
                        'col' => 3,
                        'type' => 'text',
                        'prefix' => '<i class="icon icon-envelope"></i>',
                        'desc' => $this->l('Enter a valid email address'),
                        'name' => 'PAGOEFECTIVO_ACCOUNT_EMAIL',
                        'label' => $this->l('Email'),
                    ),
                    array(
                        'type' => 'password',
                        'name' => 'PAGOEFECTIVO_ACCOUNT_PASSWORD',
                        'label' => $this->l('Password'),
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
            ),
        );
    }

    /**
     * Set values for the inputs.
     */
    protected function getConfigFormValues()
    {
        return array(
            //'PAGOEFECTIVO_LIVE_MODE' => Configuration::get('PAGOEFECTIVO_LIVE_MODE', true),
            'PAGOEFECTIVO_ACCOUNT_EMAIL' => Configuration::get('PAGOEFECTIVO_ACCOUNT_EMAIL', 'contact@prestashop.com'),
            'PAGOEFECTIVO_ACCOUNT_PASSWORD' => Configuration::get('PAGOEFECTIVO_ACCOUNT_PASSWORD', null),
        );
    }

    /**
     * Save form data.
     */
    protected function postProcess()
    {
        $form_values = $this->getConfigFormValues();

        foreach (array_keys($form_values) as $key) {
            Configuration::updateValue($key, Tools::getValue($key));
        }
    }

    public function installOrderState()
    {   
        if (!Configuration::get('PAGOEFECTIVO_OS_PENDING')
            || !Validate::isLoadedObject(new OrderState(Configuration::get('PAGOEFECTIVO_OS_PENDING')))){
            $order_state = new OrderState();
            $order_state->name = array();
            foreach (Language::getLanguages() as $language){
                if (Tools::strtolower($language['iso_code']) == 'es') {
                    $order_state->name[$language['id_lang']] = 'Pago PagoEfectivo pendiente';
                } else {
                    $order_state->name[$language['id_lang']] = 'Pending PagoEfectivo payment';
                }
            }
            $order_state->send_email = false;
            $order_state->color = '#FFD942';
            $order_state->hidden = false;
            $order_state->delivery = false;
            $order_state->logable = false;
            $order_state->invoice = false;
            if ($order_state->add()){
                $source = _PS_MODULE_DIR_.'pagoefectivo/img/logo.jpg';
                $destination = _PS_ROOT_DIR_.'/img/os/'.(int) $order_state->id.'.gif';
                copy($source, $destination);
            }
            Configuration::updateValue('PAGOEFECTIVO_OS_PENDING', (int)$order_state->id);
        }

        if (!Configuration::get('PAGOEFECTIVO_OS_EXPIRED')|| !Validate::isLoadedObject(new OrderState(Configuration::get('PAGOEFECTIVO_OS_EXPIRED')))){
            $order_state = new OrderState();
            $order_state->name = array();
            foreach (Language::getLanguages() as $language){
                if (Tools::strtolower($language['iso_code']) == 'es') {
                    $order_state->name[$language['id_lang']] = 'Pago PagoEfectivo expirado';
                } else {
                    $order_state->name[$language['id_lang']] = 'Expired PagoEfectivo payment';
                }
            }
            $order_state->send_email = false;
            $order_state->color = '#ec2e15';
            $order_state->hidden = false;
            $order_state->delivery = false;
            $order_state->logable = false;
            $order_state->invoice = false;
            if ($order_state->add()){
                $source = _PS_MODULE_DIR_.'pagoefectivo/img/logo.jpg';
                $destination = _PS_ROOT_DIR_.'/img/os/'.(int) $order_state->id.'.gif';
                copy($source, $destination);
            }
            Configuration::updateValue('PAGOEFECTIVO_OS_EXPIRED', (int)$order_state->id);
        }

        if (!Configuration::get('PAGOEFECTIVO_OS_REJECTED')|| !Validate::isLoadedObject(new OrderState(Configuration::get('PAGOEFECTIVO_OS_REJECTED')))){
            $order_state = new OrderState();
            $order_state->name = array();
            foreach (Language::getLanguages() as $language){
                if (Tools::strtolower($language['iso_code']) == 'es') {
                    $order_state->name[$language['id_lang']] = 'Pago PagoEfectivo extornado';
                } else {
                    $order_state->name[$language['id_lang']] = 'Extorned PagoEfectivo payment';
                }
            }
            $order_state->send_email = false;
            $order_state->color = '#FFD942';
            $order_state->hidden = false;
            $order_state->delivery = false;
            $order_state->logable = false;
            $order_state->invoice = false;
            if ($order_state->add()){
                $source = _PS_MODULE_DIR_.'pagoefectivo/img/logo.jpg';
                $destination = _PS_ROOT_DIR_.'/img/os/'.(int) $order_state->id.'.gif';
                copy($source, $destination);
            }
            Configuration::updateValue('PAGOEFECTIVO_OS_REJECTED', (int)$order_state->id);
        }
    }

    ////////////////HOOKS//////////////////
    /**
    * Add the CSS & JavaScript files you want to be loaded in the BO.
    */
    public function hookBackOfficeHeader()
    {
        if (Tools::getValue('module_name') == $this->name) {
            $this->context->controller->addJS($this->_path.'views/js/back.js');
            $this->context->controller->addCSS($this->_path.'views/css/back.css');
        }
    }

    /**
     * Add the CSS & JavaScript files you want to be added on the FO.
     */
    public function hookHeader()
    {
        $this->context->controller->addJS($this->_path.'/views/js/front.js');
        $this->context->controller->addCSS($this->_path.'/views/css/front.css');
    }

    /**
     * This method is used to render the payment button,
     * Take care if the button should be displayed or not.
     */
    public function hookPayment($params)
    {
        $currency_id = $params['cart']->id_currency;
        $currency = new Currency((int)$currency_id);

        if (in_array($currency->iso_code, $this->limited_currencies) == false)
            return false;

        $this->smarty->assign('module_dir', $this->_path);

        return $this->display(__FILE__, 'views/templates/hook/payment.tpl');
    }

    /**
     * This hook is used to display the order confirmation page.
     */
    public function hookPaymentReturn($params)
    {
        if ($this->active == false)
            return;

        $order = $params['objOrder'];

        if ($order->getCurrentOrderState()->id != Configuration::get('PS_OS_ERROR'))
            $this->smarty->assign('status', 'ok');

        $this->smarty->assign(array(
            'id_order' => $order->id,
            'reference' => $order->reference,
            'params' => $params,
            'total' => Tools::displayPrice($params['total_to_pay'], $params['currencyObj'], false),
        ));

        return $this->display(__FILE__, 'views/templates/hook/confirmation.tpl');
    }

    public function hookActionPaymentConfirmation()
    {
        /* Place your code here. */
    }

    public function hookDisplayPayment()
    {
        /* Place your code here. */
    }

    public function hookDisplayPaymentReturn()
    {
        /* Place your code here. */
    }
}
