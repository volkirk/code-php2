<?php
    final class Shop{
        private function __construct()
        {
            $this->connect = '��� ������-�����������(PDO)';
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
            $goods = $shopDB->connect; //������ ������ �� ��, � ����������, ��� ���������� ������ 0
            if (!empty($goods)){
                $goods = (object)['name' => '��������', 'count' => 500]; //!�������� ����� ���������� ��
                return $goods; //�������������� ������� ������� � ���������� ������, ����� � �.�.
            }else{
                return false;
            }
        }
    }
    class User{
        public function __construct($name, $VIP)
        {
            $this->name = $name;
            $this->VIP = $VIP; //� ������������ ����� ���� ������ VIP, ������ ������ 10%;
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
                    $goods->count = $goods->count*0.9; //���� � ������������ VIP, �� ������ ������
                }
                if ($_POST['delivery'] == null){
                    $goods->count = $goods->count*0.95; //���� ����� �������� �� ������ (���������), �� ��� ������ �� 5%
                }
            }
        }
    }
    class Basket{
        public function __construct(User $user)
        {
            $this->user = $user; //������������, �������� ������������ �������
            $this->complete = false; //���� true, �� ������� ��������� �������
            $this->goods = []; //������ �������
            $this->discountObserver = new Discount($this); //��������, ����������� ������
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
    $user = new User('����', true);
    $basket = new Basket($user);
    $basket->addGoods(12);
    $basket->checkout();
    var_dump($basket->goods);
?>