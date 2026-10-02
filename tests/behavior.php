<?php
declare(strict_types=1);
namespace Magento\Framework\Lock { interface LockManagerInterface { public function lock($name,$timeout=-1); public function unlock($name); } }
namespace Magento\Framework\App\Request { class Http { public bool $post=true; public string $controller='auth'; public string $action='login'; public function isPost(){return $this->post;} public function getControllerName(){return $this->controller;} public function getActionName(){return $this->action;} } }
namespace Magento\Backend\Model { class Auth {} }
namespace Magedia\Demo\Model\Reset { class SetResetTime { public int $updates=0; public function setLastResetTime():void {$this->updates++;} } }
namespace {
require __DIR__.'/../Api/ResetHandlerInterface.php';require __DIR__.'/../Model/Reset/Runner.php';require __DIR__.'/../Plugin/FixedDemoLogin.php';
function check(bool $ok,string $message):void {if(!$ok)throw new \RuntimeException($message);}
$locks=new class implements \Magento\Framework\Lock\LockManagerInterface {public bool $available=true;public int $released=0;public function lock($name,$timeout=-1){return $this->available;}public function unlock($name){$this->released++;return true;}};
$time=new \Magedia\Demo\Model\Reset\SetResetTime();$handler=new class implements \Magedia\Demo\Api\ResetHandlerInterface {public int $calls=0;public bool $fail=false;public function reset():void{$this->calls++;if($this->fail)throw new \RuntimeException('fixture failure');}};
$runner=new \Magedia\Demo\Model\Reset\Runner($time,$locks,[$handler]);check($runner->run()&&$handler->calls===1&&$time->updates===1&&$locks->released===1,'Successful reset did not complete.');
$locks->available=false;check(!$runner->run()&&$handler->calls===1,'Concurrent reset was not skipped.');$locks->available=true;$handler->fail=true;try{$runner->run();throw new \LogicException('Expected failure');}catch(\RuntimeException $e){check($e->getMessage()==='fixture failure','Wrong error');}check($time->updates===1&&$locks->released===2,'Failed reset advanced timer or retained lock.');check(!(new \Magedia\Demo\Model\Reset\Runner($time,$locks))->run(),'No-handler demo reset must be a no-op.');
$request=new \Magento\Framework\App\Request\Http();$plugin=new \Magedia\Demo\Plugin\FixedDemoLogin($request);$auth=new \Magento\Backend\Model\Auth();check($plugin->beforeLogin($auth,'autofilled-owner','wrong')===['demo','demo'],'Autofill must not select another account.');$request->post=false;check($plugin->beforeLogin($auth,'owner','secret')===['owner','secret'],'GET authentication arguments changed.');$request->post=true;$request->controller='index';$request->action='index';check($plugin->beforeLogin($auth,'autofilled-owner','wrong')===['demo','demo'],'Backend entry-point sign-in was not protected.');
echo "Reset success, lock contention, failure cleanup, no-handler isolation, and autofill/request-boundary checks passed.\n";
}
