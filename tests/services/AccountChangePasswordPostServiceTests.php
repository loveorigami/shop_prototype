<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\AccountChangePasswordPostService;
use app\tests\DbManager;
use app\tests\sources\fixtures\UsersFixture;
use app\models\UsersModel;

/**
 * Тестирует класс AccountChangePasswordPostService
 */
class AccountChangePasswordPostServiceTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'users'=>UsersFixture::class,
            ],
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        \Yii::$app->registry->clean();
        \Yii::$app->user->logout();
    }
    
    /**
     * Тестирует метод AccountChangePasswordPostService::handle
     * если запрос с ошибками
     */
    public function testHandleErrors()
    {
        $user = UsersModel::findOne(1);
        \Yii::$app->user->login($user);
        
        $request = new class() {
            public $isAjax = true;
            public function post($name = null, $defaultValue = null)
            {
                return [
                    'UserChangePasswordForm'=>[
                        'currentPassword'=>'jFpi8Uh',
                        'password'=>'LOj7yH',
                        'password2'=>'aPolkfj',
                    ]
                ];
            }
        };
        
        $service = new AccountChangePasswordPostService();
        
        $result = $service->handle($request);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
    }
    
    /**
     * Тестирует метод AccountChangePasswordPostService::handle
     */
    public function testHandle()
    {
        $user = UsersModel::findOne(1);
        $rawPassword = $user->password;
        
        \Yii::$app->db->createCommand('UPDATE {{users}} SET [[password]]=:password WHERE [[id]]=:id')->bindValues([':password'=>password_hash($rawPassword, PASSWORD_DEFAULT), ':id'=>$user->id])->execute();
        
        $user = UsersModel::findOne(1);
        \Yii::$app->user->login($user);
        
        $request = new class() {
            public $isAjax = true;
            public $currentPassword;
            public function post($name = null, $defaultValue = null)
            {
                return [
                    'UserChangePasswordForm'=>[
                        'currentPassword'=>$this->currentPassword,
                        'password'=>'LOj7yH',
                        'password2'=>'LOj7yH',
                    ]
                ];
            }
        };
        $reflection = new \ReflectionProperty($request, 'currentPassword');
        $reflection->setValue($request, $rawPassword);
        
        $service = new AccountChangePasswordPostService();
        $result = $service->handle($request);
        
        $this->assertInternalType('string', $result);
        $this->assertNotEmpty($result);
        
        $result = \Yii::$app->db->createCommand('SELECT * FROM {{users}} WHERE [[id]]=:id')->bindValue(':id', $user->id)->queryOne();
        
        $this->assertTrue(password_verify('LOj7yH', $result['password']));
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
