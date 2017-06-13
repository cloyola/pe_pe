<?php

class PagoEfectivoPaymentModuleFrontController extends ModuleFrontController
{
    public $ssl = true;

    public function initContent()
    {
        // Disable left and right column
        $this->display_column_left = false;
        $this->display_column_right = false;

        // Call parent init content method
        parent::initContent();

        // Check if currency is accepted
        if (!$this->checkCurrency())
            Tools::redirect('index.php?controller=order');

        $shop = new Shop(Configuration::get('PS_SHOP_DEFAULT'));
        $home_url = Tools::getShopProtocol().$shop->domain.$shop->getBaseURI();

        // Assign data to Smarty
        $this->context->smarty->assign(array(
            'nb_products' => $this->context->cart->nbProducts(),
            'cart_currency' => $this->context->cart->id_currency,
            'currencies' => $this->module->getCurrency((int)$this->context->cart->id_currency),
            'total_amount' => $this->context->cart->getOrderTotal(true, Cart::BOTH),
            'path' => $this->module->getPathUri(),
            'home_url' => $home_url,
            'pe_internet' => Configuration::get('PE_INTERNET'),
            'pe_agencias' => Configuration::get('PE_AGENCIAS'),
            'pe_pregunta' => Configuration::get('PE_PREGUNTA'),
            'pe_url' => Configuration::get('PE_URL'),
        ));

        // Set template
        $this->setTemplate('payment.tpl');
    }

    private function checkCurrency()
    {
        // Get cart currency and enabled currencies for this module
        $currency_order = new Currency($this->context->cart->id_currency);
        $currencies_module = $this->module->getCurrency($this->context->cart->id_currency);
        // Check if cart currency is one of the enabled currencies
        if (is_array($currencies_module))
            foreach ($currencies_module as $currency_module)
                if ($currency_order->id == $currency_module['id_currency'])
                    return true;
        // Return false otherwise
        return false;
    }
}
