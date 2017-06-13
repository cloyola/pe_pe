<?php

//Configuracion de entorno
define('PE_SERVER', Configuration::get('PE_CONFIG_SERVIDOR'));
//Rutas de Webservices
$wsCrypta = PE_SERVER.'PagoEfectivoWSCrypto/WSCrypto.asmx';	
define('PE_WSCRYPTA', $wsCrypta.'?wsdl');
//define('PE_WSCIP', PE_SERVER.'PagoEfectivoWSGeneral/WSCIP.asmx?wsdl');
$wsGenPago = PE_SERVER.'GenPago.aspx';
define('PE_WSGENPAGO', $wsGenPago);
$wsGenPagoIframe = PE_SERVER.'GenPagoIF.aspx';
define('PE_WSGENPAGOIFRAME', $wsGenPagoIframe);
//Para las modalidades
$wsGenCIP = PE_SERVER.'PagoEfectivoWSGeneralv2/service.asmx';
define('PE_WSGENCIP', $wsGenCIP.'?wsdl');
$wsCryptab = PE_SERVER.'pasarela/pasarela/crypta.asmx';	
define('PE_WSCRYPTAB', $wsCryptab.'?wsdl');
//Configuracion de cuenta
//Mail de la persona a la que le llegara el mail en la prueba de generacion de cip
//Este mail es de prueba, al final en vez de esta constante - se reemplazará con el mail del cliente
define('PE_EMAIL_PORTAL', Configuration::get('PE_COMERCIO_EMAIL'));
define('PE_EMAIL_CONTACTO', Configuration::get('PE_COMERCIO_EMAIL'));
//Tiempo en el que expira el código cip para pagarlo en el banco. Se mide en horas
define('EXPIRACION_DIA', Configuration::get('PE_CONFIG_DIA'));
//Este dato es unico por servicio - nosotros se lo proporcionaremos
define('PE_MERCHAND_ID', Configuration::get('PE_CONFIG_ID'));
//Nombre del portal para el concepto de Pago que acompaña al numero de pedido en el banco
define('PE_COMERCIO_CONCEPTO_PAGO', strtoupper(Configuration::get('PE_COMERCIO_NOMBRE')));
//El dominio de pruebas o produccion al que solicitaron permisos por IP
$shop = new Shop(Configuration::get('PS_SHOP_DEFAULT'));
$home_url = Tools::getShopProtocol().$shop->domain.$shop->getBaseURI();
define('PE_DOMINIO_COMERCIO', $home_url);
//ubicacion y nombre de los archivos a usar
define('PE_PATH', _PS_ROOT_DIR_);
define('PE_SECURITY_PATH', PE_PATH . '/upload/pagoefectivo/key');
//Estos archivos se los enviara PagoEfectivo
//nombre del archivo clave publica de PagoEfectivo
define('PE_PUBLICKEY', Configuration::get('PE_CONFIG_KEYPUBLICA_NOMBRE'));
//nombre del archivo clave privada del comercio - cambiar con el prefijo de la llave - por el valor de MERCHAN_ID indicado
define('PE_PRIVATEKEY', Configuration::get('PE_CONFIG_KEYPRIVADA_NOMBRE'));
//Modo de integración
define('PE_MOD_INTEGRACION', Configuration::get('PE_CONFIG_MODO'));
define('PE_MEDIO_PAGO','1,2');