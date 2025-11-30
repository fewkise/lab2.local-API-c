<?php

class Depositor
{
    private $conn;
    private $table_vkladchik = "vkladchik"; 
    private $table_procent = "procent";    

    public $nomer_vklada;
    public $nazvanie_vklada;
    public $fio_vkladchika;
    public $summa_vklada;
    public $data_vlozheniya;
    public $procent_nachisleniya;
    public $obshchaya_summa_s_nachisleniyami;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function read()
    {
        $query = "SELECT
                    `номер вклада`,
                    `название вклада`,
                    `ФИО вкладчика`,
                    `сумма вклада`,
                    `дата вложения`,
                    `процент начисления`,
                    `общая сумма с начислениями`
                FROM
                    " . $this->table_vkladchik . "
                ORDER BY
                    `название вклада` ASC, `ФИО вкладчика` ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function getTotalInterestSum()
    {
        $query = "SELECT
                    SUM(`общая сумма с начислениями` - `сумма вклада`) AS total_interest
                FROM
                    " . $this->table_vkladchik;

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total_interest'];
    }
    

    public function create($data)
    {
        $query = "INSERT INTO " . $this->table_vkladchik . "
                SET
                    `номер вклада`=:n_vklada,
                    `название вклада`=:nazv_vklada,
                    `ФИО вкладчика`=:fio,
                    `сумма вклада`=:summa,
                    `дата вложения`=:data,
                    `процент начисления`=:procent,
                    `общая сумма с начислениями`=:total_sum";

        $stmt = $this->conn->prepare($query);
        $summa_vklada = $data['сумма_вклада'];
        $procent_nachisleniya = $data['процент_начисления'];
        $total_sum = $summa_vklada * (1 + $procent_nachisleniya / 100);

        $stmt->bindParam(":n_vklada", htmlspecialchars(strip_tags($data['номер_вклада'])));
        $stmt->bindParam(":nazv_vklada", htmlspecialchars(strip_tags($data['название_вклада'])));
        $stmt->bindParam(":fio", htmlspecialchars(strip_tags($data['фио_вкладчика'])));
        $stmt->bindParam(":summa", $summa_vklada);
        $stmt->bindParam(":data", $data['дата_вложения']);
        $stmt->bindParam(":procent", $procent_nachisleniya);
        $stmt->bindParam(":total_sum", $total_sum);
    
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function update($data)
    {
        $query = "UPDATE " . $this->table_vkladchik . "
                SET
                    `название вклада`=:nazv_vklada,
                    `сумма вклада`=:summa,
                    `дата вложения`=:data,
                    `процент начисления`=:procent,
                    `общая сумма с начислениями`=:total_sum
                WHERE
                    `ФИО вкладчика`=:fio AND `номер вклада`=:n_vklada";

        $stmt = $this->conn->prepare($query);

        $summa_vklada = $data['сумма_вклада'];
        $procent_nachisleniya = $data['процент_начисления'];
        $total_sum = $summa_vklada * (1 + $procent_nachisleniya / 100);

        $stmt->bindParam(":nazv_vklada", htmlspecialchars(strip_tags($data['название_вклада'])));
        $stmt->bindParam(":summa", $summa_vklada);
        $stmt->bindParam(":data", $data['дата_вложения']);
        $stmt->bindParam(":procent", $procent_nachisleniya);
        $stmt->bindParam(":total_sum", $total_sum); 
        $stmt->bindParam(":fio", htmlspecialchars(strip_tags($data['фио_вкладчика'])));
        $stmt->bindParam(":n_vklada", htmlspecialchars(strip_tags($data['номер_вклада'])));

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function delete($fio, $nomer_vklada)
    {
        $query = "DELETE FROM " . $this->table_vkladchik . " WHERE `ФИО вкладчика` = :fio AND `номер вклада` = :n_vklada";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":fio", htmlspecialchars(strip_tags($fio)));
        $stmt->bindParam(":n_vklada", htmlspecialchars(strip_tags($nomer_vklada)));
        if ($stmt->execute()) { return true; }
        return false;
    }
}
?>
