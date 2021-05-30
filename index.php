<?php
    final class Shop{
        private function __construct()
        {
            $this->connect = 'тут объект-подключение(PDO)';
        }
        private function __clone()
        {
        }
        private static $db = null;
        public static function get_db_connection(){
            if (self::$db === null){
                self::$db = new self();
                return self::$db;
            }else{
                return self::$db;
            }
        }
    }
    class Goods{
        public function getGoods($goodsID){
            $shopDB = Shop::get_db_connection();
            $goods = $shopDB->connect; //Запрос товара из бд, с уточнением, что количество больше 0
            if (!empty($goods)){
                $goods = (object)['name' => 'Одеколон', 'count' => 500]; //!Имитация ввиду отсутствия БД
                return $goods; //предполагается возврат объекта с называнием товара, ценой и т.д.
            }else{
                return false;
            }
        }
    }
    class User{
        public function __construct($name, $VIP)
        {
            $this->name = $name;
            $this->VIP = $VIP; //у пользователя может быть статус VIP, дающий скидку 10%;
        }
    }
    class Discount{
        public function __construct(Basket $basket)
        {
            $this->basket = $basket;
        }
        public function applyDiscounts(){
            foreach ($this->basket->goods as $goods){
                if($this->basket->user->VIP === true){
                    $goods->count = $goods->count*0.9; //если у пользователя VIP, то дается скидка
                }
                if ($_POST['delivery'] == null){
                    $goods->count = $goods->count*0.95; //если адрес доставки не указан (самовывоз), то еще скидка на 5%
                }
            }
        }
    }
    class Basket{
        public function __construct(User $user)
        {
            $this->user = $user; //Пользователь, которому принадлженит корзина
            $this->complete = false; //если true, то корзина считается заказом
            $this->goods = []; //список товаров
            $this->discountObserver = new Discount($this); //Обсервер, применяющий скидки
        }
        public function addGoods($id){
            $request = new Goods;
            $goods = $request->getGoods($id);
            if($goods){
                $this->goods[] = $goods;
            }
        }
        public function checkout(){
            $this->discountObserver->applyDiscounts();
            $this->complete = true;
        }
    }
    $user = new User('Вася', true);
    $basket = new Basket($user);
    $basket->addGoods(12);
    $basket->checkout();
    var_dump($basket->goods);
?>