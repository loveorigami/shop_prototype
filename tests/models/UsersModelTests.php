<?php

namespace app\test\models;

use app\tests\DbManager;
use app\models\UsersModel;
use app\models\RulesModel;
use app\models\EmailsModel;
use app\models\AddressModel;
use app\models\PhonesModel;
use app\models\DeliveriesModel;
use app\models\PaymentsModel;

/**
 * Тестирует UsersModel
 */
class UsersModelTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_reflectionClass;
    private static $_id = 1;
    private static $_login = 'some';
    private static $_login2 = 'some next';
    private static $_password = 'ghht4de';
    private static $_name = 'Some';
    private static $_surname = 'Some';
    private static $_rulesFromForm = [1,2];
    private static $_id_emails = 2;
    private static $_id_phones = 3;
    private static $_id_address = 5;
    private static $_email = 'some@some.com';
    private static $_phone = '+3806589785645';
    private static $_address = 'Some Address';
    private static $_city = 'Some city';
    private static $_country = 'Some country';
    private static $_postcode = 'F12345';
    private static $_rule = 'Some Rule';
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
        self::$_reflectionClass = new \ReflectionClass('app\models\UsersModel');
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{emails}} SET [[id]]=:id, [[email]]=:email');
        $command->bindValues([':id'=>self::$_id, ':email'=>self::$_email]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{phones}} SET [[id]]=:id, [[phone]]=:phone');
        $command->bindValues([':id'=>self::$_id, ':phone'=>self::$_phone]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{address}} SET [[id]]=:id, [[address]]=:address, [[city]]=:city, [[country]]=:country, [[postcode]]=:postcode');
        $command->bindValues([':id'=>self::$_id, ':address'=>self::$_address, ':city'=>self::$_city, ':country'=>self::$_country, ':postcode'=>self::$_postcode]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{users}} SET [[id]]=:id, [[login]]=:login, [[name]]=:name, [[surname]]=:surname, [[id_emails]]=:id_emails, [[id_phones]]=:id_phones, [[id_address]]=:id_address');
        $command->bindValues([':id'=>self::$_id, ':login'=>self::$_login, ':name'=>self::$_name, ':surname'=>self::$_surname, ':id_emails'=>self::$_id, ':id_phones'=>self::$_id, ':id_address'=>self::$_id]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{rules}} SET [[id]]=:id, [[rule]]=:rule');
        $command->bindValues([':id'=>self::$_id, ':rule'=>self::$_rule]);
        $command->execute();
    }
    
    /**
     * Тестирует наличие свойств, констант и методов
     */
    public function testProperties()
    {
        $model = new UsersModel();
        
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_FORM'));
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_CART_FORM'));
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_DB'));
        
        $this->assertTrue(property_exists($model, 'name'));
        $this->assertTrue(property_exists($model, 'surname'));
        $this->assertTrue(property_exists($model, 'id_emails'));
        $this->assertTrue(property_exists($model, 'id_phones'));
        $this->assertTrue(property_exists($model, 'id_address'));
        $this->assertTrue(property_exists($model, 'rawPassword'));
        $this->assertTrue(property_exists($model, 'rulesFromForm'));
        $this->assertTrue(property_exists($model, '_login'));
        $this->assertTrue(property_exists($model, '_id'));
        $this->assertTrue(property_exists($model, '_password'));
        $this->assertTrue(property_exists($model, '_allRules'));
        $this->assertTrue(property_exists($model, '_emails'));
        $this->assertTrue(property_exists($model, '_address'));
        $this->assertTrue(property_exists($model, '_phones'));
        $this->assertTrue(property_exists($model, '_deliveries'));
        $this->assertTrue(property_exists($model, '_payments'));
        
        $this->assertTrue(method_exists($model, 'setPassword'));
        $this->assertTrue(method_exists($model, 'getPassword'));
        $this->assertTrue(method_exists($model, 'getAllRules'));
        $this->assertTrue(method_exists($model, 'getId'));
        $this->assertTrue(method_exists($model, 'setId'));
        $this->assertTrue(method_exists($model, 'getLogin'));
        $this->assertTrue(method_exists($model, 'setLogin'));
        $this->assertTrue(method_exists($model, 'getEmails'));
        $this->assertTrue(method_exists($model, 'setEmails'));
        $this->assertTrue(method_exists($model, 'getAddress'));
        $this->assertTrue(method_exists($model, 'setAddress'));
        $this->assertTrue(method_exists($model, 'getPhones'));
        $this->assertTrue(method_exists($model, 'setPhones'));
        $this->assertTrue(method_exists($model, 'getDeliveries'));
        $this->assertTrue(method_exists($model, 'setDeliveries'));
        $this->assertTrue(method_exists($model, 'getPayments'));
        $this->assertTrue(method_exists($model, 'setPayments'));
    }
    
    /**
     * Тестирует сценарии
     */
    public function testScenarios()
    {
        $model = new UsersModel(['scenario'=>UsersModel::GET_FROM_FORM]);
        $model->attributes = ['id'=>self::$_id, 'login'=>self::$_login2, 'password'=>self::$_password, 'name'=>self::$_name, 'surname'=>self::$_surname, 'rulesFromForm'=>self::$_rulesFromForm];
        
        $this->assertTrue(empty($model->id));
        $this->assertFalse(empty($model->login));
        $this->assertFalse(empty($model->password));
        $this->assertFalse(empty($model->name));
        $this->assertFalse(empty($model->surname));
        $this->assertFalse(empty($model->rulesFromForm));
        
        $this->assertEquals(self::$_login2, $model->login);
        $this->assertTrue(password_verify(self::$_password, $model->password));
        $this->assertEquals(self::$_name, $model->name);
        $this->assertEquals(self::$_surname, $model->surname);
        $this->assertEquals(self::$_rulesFromForm, $model->rulesFromForm);
        
        $model = new UsersModel(['scenario'=>UsersModel::GET_FROM_DB]);
        $model->attributes = ['id'=>self::$_id, 'login'=>self::$_login, 'password'=>self::$_password, 'name'=>self::$_name, 'surname'=>self::$_surname, 'id_emails'=>self::$_id_emails, 'id_phones'=>self::$_id_phones, 'id_address'=>self::$_id_address, 'rulesFromForm'=>self::$_rulesFromForm];
        
        $this->assertFalse(empty($model->id));
        $this->assertFalse(empty($model->login));
        $this->assertFalse(empty($model->password));
        $this->assertFalse(empty($model->name));
        $this->assertFalse(empty($model->surname));
        $this->assertFalse(empty($model->id_emails));
        $this->assertFalse(empty($model->id_phones));
        $this->assertFalse(empty($model->id_address));
        $this->assertTrue(empty($model->rulesFromForm));
        
        $this->assertEquals(self::$_id, $model->id);
        $this->assertEquals(self::$_login, $model->login);
        $this->assertTrue(password_verify(self::$_password, $model->password));
        $this->assertEquals(self::$_name, $model->name);
        $this->assertEquals(self::$_surname, $model->surname);
        $this->assertEquals(self::$_id_emails, $model->id_emails);
        $this->assertEquals(self::$_id_phones, $model->id_phones);
        $this->assertEquals(self::$_id_address, $model->id_address);
        
        $model = new UsersModel(['scenario'=>UsersModel::GET_FROM_CART_FORM]);
        $model->attributes = ['name'=>self::$_name, 'surname'=>self::$_surname, 'id_emails'=>self::$_id_emails];
        
        $this->assertFalse(empty($model->name));
        $this->assertFalse(empty($model->surname));
        $this->assertTrue(empty($model->id_emails));
        
        $this->assertEquals(self::$_name, $model->name);
        $this->assertEquals(self::$_surname, $model->surname);
    }
    
    /**
     * Тестирует правила проверки
     */
    public function testRules()
    {
        $model = new UsersModel(['scenario'=>UsersModel::GET_FROM_FORM]);
        $model->attributes = [];
        $model->validate();
        
        $this->assertEquals(3, count($model->errors));
        $this->assertTrue(array_key_exists('login', $model->errors));
        $this->assertTrue(array_key_exists('password', $model->errors));
        $this->assertTrue(array_key_exists('rulesFromForm', $model->errors));
        
        $model = new UsersModel(['scenario'=>UsersModel::GET_FROM_FORM]);
        $model->attributes = ['login'=>self::$_login, 'password'=>self::$_password, 'rulesFromForm'=>self::$_rulesFromForm];
        $model->validate();
        
        $this->assertEquals(1, count($model->errors));
        $this->assertTrue(array_key_exists('login', $model->errors));
        
        $model = new UsersModel(['scenario'=>UsersModel::GET_FROM_FORM]);
        $model->attributes = ['login'=>self::$_login2, 'password'=>self::$_password, 'rulesFromForm'=>self::$_rulesFromForm];
        $model->validate();
        
        $this->assertEquals(0, count($model->errors));
        
        $model = new UsersModel(['scenario'=>UsersModel::GET_FROM_CART_FORM]);
        $model->attributes = [];
        $model->validate();
        
        $this->assertEquals(2, count($model->errors));
        $this->assertTrue(array_key_exists('name', $model->errors));
        $this->assertTrue(array_key_exists('surname', $model->errors));
        
        $model = new UsersModel(['scenario'=>UsersModel::GET_FROM_CART_FORM]);
        $model->attributes = ['name'=>self::$_name, 'surname'=>self::$_surname];
        $model->validate();
        
        $this->assertEquals(0, count($model->errors));
    }
    
    /**
     * Тестирует метод UsersModel::setPassword
     */
    public function testSetPassword()
    {
        $model = new UsersModel();
        $model->password = self::$_password;
        
        $this->assertTrue(password_verify(self::$_password, $model->password));
    }
    
    /**
     * Тестирует метод UsersModel::getPassword
     */
    public function testGetPassword()
    {
        $model = new UsersModel();
        $model->name = self::$_name;
        
        $createdPassword = $model->password;
        
        $this->assertTrue(empty($createdPassword));
        $this->assertTrue(empty($model->rawPassword));
        
        $model = new UsersModel(['scenario'=>UsersModel::GET_FROM_CART_FORM]);
        $model->name = self::$_name;
        
        $createdPassword = $model->password;
        
        $this->assertFalse(empty($createdPassword));
        $this->assertFalse(empty($model->rawPassword));
    }
    
    /**
     * Тестирует метод UsersModel::getAllRules
     */
    public function testGetAllRules()
    {
        $model = new UsersModel();
        
        $rulesArray = $model->allRules;
        
        $this->assertTrue(is_array($rulesArray));
        $this->assertFalse(empty($rulesArray));
        $this->assertTrue(is_object($rulesArray[0]));
        $this->assertTrue($rulesArray[0] instanceof RulesModel);
    }
    
    /**
     * Тестирует метод UsersModel::setId
     */
    public function testSetId()
    {
        $model = new UsersModel();
        $model->id = self::$_id + 14;
        
        $this->assertEquals(self::$_id + 14, $model->id);
    }
    
    /**
     * Тестирует метод UsersModel::getId
     */
    public function testGetId()
    {
        $model = new UsersModel();
        $model->login = self::$_login;
        
        $this->assertEquals(self::$_id, $model->id);
    }
    
    /**
     * Тестирует выброс исключения в методе UsersModel::getId
     * @expectedException ErrorException
     */
    public function testExcGetId()
    {
        $model = new UsersModel();
        //$model->login = self::$_login;
        
        $this->assertEquals(self::$_id, $model->id);
    }
    
    /**
     * Тестирует метод UsersModel::setLogin
     */
    public function testSetLogin()
    {
        $model = new UsersModel();
        
        $model->login = self::$_login . 'now';
        
        $this->assertEquals(self::$_login . 'now', $model->login);
    }
    
    /**
     * Тестирует метод UsersModel::getLogin
     */
    public function testGetLogin()
    {
        $model = new UsersModel();
        $model->name = self::$_name;
        
        $createdLogin = $model->login;
        
        $this->assertTrue(empty($createdLogin));
        
        $model = new UsersModel(['scenario'=>UsersModel::GET_FROM_CART_FORM]);
        $model->name = self::$_name;
        
        $createdLogin = $model->login;
        
        $this->assertFalse(empty($createdLogin));
    }
    
    /**
     * Тестирует метод UsersModel::setEmails
     */
    public function testSetEmails()
    {
        $model = new UsersModel();
        $model->emails = new EmailsModel();
        
        $this->assertTrue(is_object($model->emails));
        $this->assertTrue($model->emails instanceof EmailsModel);
    }
    
    /**
     * Тестирует метод UsersModel::getEmails
     */
    public function testGetEmails()
    {
        $model = new UsersModel();
        $model->emails = new EmailsModel();
        
        $this->assertTrue(is_object($model->emails));
        $this->assertTrue($model->emails instanceof EmailsModel);
    }
    
    /**
     * Тестирует метод UsersModel::setAddress
     */
    public function testSetAddress()
    {
        $model = new UsersModel();
        $model->address = new AddressModel();
        
        $this->assertTrue(is_object($model->address));
        $this->assertTrue($model->address instanceof AddressModel);
    }
    
    /**
     * Тестирует метод UsersModel::getAddress
     */
    public function testGetAddress()
    {
        $model = new UsersModel();
        $model->address = new AddressModel();
        
        $this->assertTrue(is_object($model->address));
        $this->assertTrue($model->address instanceof AddressModel);
    }
    
    /**
     * Тестирует метод UsersModel::setPhones
     */
    public function testSetPhones()
    {
        $model = new UsersModel();
        $model->phones = new PhonesModel();
        
        $this->assertTrue(is_object($model->phones));
        $this->assertTrue($model->phones instanceof PhonesModel);
    }
    
    /**
     * Тестирует метод UsersModel::getPhones
     */
    public function testGetPhones()
    {
        $model = new UsersModel();
        $model->phones = new PhonesModel();
        
        $this->assertTrue(is_object($model->phones));
        $this->assertTrue($model->phones instanceof PhonesModel);
    }
    
    /**
     * Тестирует метод UsersModel::setDeliveries
     */
    public function testSetDeliveries()
    {
        $model = new UsersModel();
        $model->deliveries = new DeliveriesModel();
        
        $this->assertTrue(is_object($model->deliveries));
        $this->assertTrue($model->deliveries instanceof DeliveriesModel);
    }
    
    /**
     * Тестирует метод UsersModel::getDeliveries
     */
    public function testGetDeliveries()
    {
        $model = new UsersModel();
        $model->deliveries = new DeliveriesModel();
        
        $this->assertTrue(is_object($model->deliveries));
        $this->assertTrue($model->deliveries instanceof DeliveriesModel);
    }
    
    /**
     * Тестирует метод UsersModel::setPayments
     */
    public function testSetPayments()
    {
        $model = new UsersModel();
        $model->payments = new PaymentsModel();
        
        $this->assertTrue(is_object($model->payments));
        $this->assertTrue($model->payments instanceof PaymentsModel);
    }
    
    /**
     * Тестирует метод UsersModel::getPayments
     */
    public function testGetPayments()
    {
        $model = new UsersModel();
        $model->payments = new PaymentsModel();
        
        $this->assertTrue(is_object($model->payments));
        $this->assertTrue($model->payments instanceof PaymentsModel);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}