<?php
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
require_once('function.php');

CModule::IncludeModule('iblock');

$error = array();
$result = array();

$user_id = 0;

if($_SESSION['USER_DATA']) {
    $user_id = $_SESSION['USER_DATA']['ID'];
}

$input = filter_input_array(INPUT_POST);

$id          = (int) $input['id'];
$type        = $input['type'];
$userCurrent = (int) $input['user'];
$post        = (int) $input['post'];

if($id && $type && $userCurrent && isEdit()) {

    if($type === 'del-avatar') {

        $user = new CUser;
        $fields = Array(
            "PERSONAL_PHOTO" => CFile::MakeFileArray(SITE_TEMPLATE_PATH . "/images/user-1.png"),
        );
        $user->Update($userCurrent, $fields);

        CIBlockElement::SetPropertyValueCode($id, "DEL_AVATAR", 'Y');

        $arFilterDel = Array("IBLOCK_ID" => 25, "PROPERTY_OWNER" => $userCurrent, "PROPERTY_DEL_AVATAR" => "Y");
        $cntDel = CIBlockElement::GetList(array(), $arFilterDel, Array(), false, Array());
        $result["DEL_AVATAR_CNT"] = $cntDel ? $cntDel : 0;

        $result['PIC'] = SITE_TEMPLATE_PATH . "/images/user-1.png";

    } elseif($type === 'reject') {

        CIBlockElement::SetPropertyValueCode($id, "REJECT", 'Y');

        $arFilterRej = Array("IBLOCK_ID" => 25, "PROPERTY_OWNER" => $userCurrent, "PROPERTY_REJECT" => "Y");
        $cntRej = CIBlockElement::GetList(array(), $arFilterRej, Array(), false, Array());
        $result["REJECT_CNT"] = $cntRej ? $cntRej : 0;

    } elseif($type === 'warning') {

        CIBlockElement::SetPropertyValueCode($id, "WARNING", 'Y');

        $arFilterWarning = Array("IBLOCK_ID" => 25, "PROPERTY_OWNER" => $userCurrent, "PROPERTY_WARNING" => "Y");
        $cntWarning = CIBlockElement::GetList(array(), $arFilterWarning, Array(), false, Array());
        $result["WARNING_CNT"] = $cntWarning ? $cntWarning : 0;

    } elseif($type === 'ban') {

    } elseif($type === 'del-user') {

        $arId     = array();

        $arSelect = array("ID", "IBLOCK_ID");
        $arFilter = array("IBLOCK_ID" => 25, "PROPERTY_OWNER" => $userCurrent);
        $res = CIBlockElement::GetList(array("NAME" => "ASC"), $arFilter, false, false, $arSelect);
        while($row = $res->GetNext()) {
            $arId[] = $row["ID"];
        }

        foreach($arId as $item) {
            CIBlockElement::Delete($item);
        }

        $sql = "DELETE FROM a_bookmark WHERE user_id = :user_id";
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':user_id', $userCurrent, PDO::PARAM_INT);
        $stmt->execute();

        $sql = "DELETE FROM a_like WHERE id_user = :id_user";
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':id_user', $userCurrent, PDO::PARAM_INT);
        $stmt->execute();

        $sql = "DELETE FROM a_deslike WHERE id_user = :id_user";
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':id_user', $userCurrent, PDO::PARAM_INT);
        $stmt->execute();

        $sql = "DELETE FROM a_like_events WHERE id_user = :id_user";
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':id_user', $userCurrent, PDO::PARAM_INT);
        $stmt->execute();

        $sql = "DELETE FROM a_deslike_events WHERE id_user = :id_user";
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':id_user', $userCurrent, PDO::PARAM_INT);
        $stmt->execute();

        $sql = "DELETE FROM a_like_news WHERE id_user = :id_user";
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':id_user', $userCurrent, PDO::PARAM_INT);
        $stmt->execute();

        $sql = "DELETE FROM a_deslike_news WHERE id_user = :id_user";
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':id_user', $userCurrent, PDO::PARAM_INT);
        $stmt->execute();

        $sql = "DELETE FROM a_like_user WHERE id_user = :id_user";
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':id_user', $userCurrent, PDO::PARAM_INT);
        $stmt->execute();

        $sql = "DELETE FROM a_deslike_user WHERE id_user = :id_user";
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':id_user', $userCurrent, PDO::PARAM_INT);
        $stmt->execute();

        $sql = "DELETE FROM a_user_uz WHERE user_id = :user_id";
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':user_id', $userCurrent, PDO::PARAM_INT);
        $stmt->execute();

        $sql = "DELETE FROM a_events_go WHERE id_user = :id_user";
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':id_user', $userCurrent, PDO::PARAM_INT);
        $stmt->execute();

        $sql = "DELETE FROM a_chat WHERE owner_id = :owner_id OR from_id = :from_id";
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':owner_id', $userCurrent, PDO::PARAM_INT);
        $stmt->bindParam(':from_id', $userCurrent, PDO::PARAM_INT);
        $stmt->execute();

        $groups = $dbh->query('SELECT group_chat from a_chat_support WHERE group_owner = ' . $userCurrent . ' GROUP BY group_chat ORDER BY id ASC')->fetchAll();

        foreach($groups as $groupItem) {
            CIBlockElement::Delete($groupItem);
        }

        $sql = "DELETE FROM a_chat_support WHERE group_owner = :group_owner";
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':group_owner', $userCurrent, PDO::PARAM_INT);
        $stmt->execute();

        CUser::Delete($userCurrent);

        $result['OUT'] = $arId;

    } elseif($type === 'del-post') {

        $arSelect = array("ID", "IBLOCK_ID", "PROPERTY_POST_ID");
        $arFilter = array("IBLOCK_ID" => 25, "ID" => $id);
        $res = CIBlockElement::GetList(array("ID" => "ASC"), $arFilter, false, false, $arSelect);
        if($row = $res->GetNext()) {

            $idPost = $row['PROPERTY_POST_ID_VALUE'];

            $stmt= $dbh->prepare("UPDATE a_chat SET del_owner = 1, del_to = 1 WHERE id = :id");
            $stmt->bindParam(':id', $idPost, PDO::PARAM_INT);
            $stmt->execute();
        }

        CIBlockElement::SetPropertyValueCode($id, "DEL", 'Y');

    } elseif($type === 'del-chat') {

        $current = $dbh->query('SELECT * from a_chat WHERE id = ' . $post . ' ORDER BY id ASC')->fetch();

        if($current['group_chat']) {
            /*** Удаление группового чата ***/

            $arrDelIds = array();
            $chatIds = $dbh->query('SELECT id from a_chat WHERE group_chat = ' . $current['group_chat'])->fetchAll();
            foreach($chatIds as $item)
                $arrDelIds[] = $item['id'];

            if($arrDelIds) {
                $arSelect = array("ID", "IBLOCK_ID", "PROPERTY_POST_ID");
                $arFilter = array("IBLOCK_ID" => 25, "PROPERTY_POST_ID" => $arrDelIds);
                $res = CIBlockElement::GetList(array("ID" => "ASC"), $arFilter, false, false, $arSelect);
                while($row = $res->GetNext()) {
                    CIBlockElement::SetPropertyValueCode($row['ID'], "CHAT", 'Y');
                }
            }

            $sql = "DELETE FROM a_chat WHERE group_chat = :group_chat";
            $stmt = $dbh->prepare($sql);
            $stmt->bindParam(':group_chat', $current['group_chat'], PDO::PARAM_INT);
            $stmt->execute();

            $sql = "DELETE FROM a_group_admin WHERE chat_id = :chat_id";
            $stmt = $dbh->prepare($sql);
            $stmt->bindParam(':chat_id', $current['group_chat'], PDO::PARAM_INT);
            $stmt->execute();

            $sql = "DELETE FROM a_group_chat WHERE id = :id";
            $stmt = $dbh->prepare($sql);
            $stmt->bindParam(':id', $current['group_chat'], PDO::PARAM_INT);
            $stmt->execute();

            $sql = "DELETE FROM a_group_del_local WHERE chat_id = :chat_id";
            $stmt = $dbh->prepare($sql);
            $stmt->bindParam(':chat_id', $current['group_chat'], PDO::PARAM_INT);
            $stmt->execute();

            $sql = "DELETE FROM a_group_user WHERE chat_id = :chat_id";
            $stmt = $dbh->prepare($sql);
            $stmt->bindParam(':chat_id', $current['group_chat'], PDO::PARAM_INT);
            $stmt->execute();

            $sql = "DELETE FROM a_user_success WHERE chat_id = :chat_id";
            $stmt = $dbh->prepare($sql);
            $stmt->bindParam(':chat_id', $current['group_chat'], PDO::PARAM_INT);
            $stmt->execute();
        } else {
            /*** Удаление p2p чата ***/

            $stmt = $dbh->prepare("SELECT id from a_chat WHERE (owner_id = :owner_id AND from_id = :from_id) OR (owner_id = :from_id AND from_id = :owner_id)");
            $stmt->execute(array(':owner_id' => $current['owner_id'], ':from_id' => $current['from_id']));
            $postsId = $stmt->fetchAll();

            if($postsId) {
                $arrDelIds = array();
                foreach ($postsId as $item) {
                    $arrDelIds[] = $item['id'];
                }

                if ($arrDelIds) {
                    $arSelect = array("ID", "IBLOCK_ID", "PROPERTY_POST_ID");
                    $arFilter = array("IBLOCK_ID" => 25, "PROPERTY_POST_ID" => $arrDelIds);
                    $res = CIBlockElement::GetList(array("ID" => "ASC"), $arFilter, false, false, $arSelect);
                    while ($row = $res->GetNext()) {
                        CIBlockElement::SetPropertyValueCode($row['ID'], "CHAT", 'Y');
                    }
                }

                $in = str_repeat('?,', count($arrDelIds) - 1) . '?';
                $sql = "DELETE FROM a_user_success WHERE post_id IN (" . $in . ")";
                $stmt = $dbh->prepare($sql);
                $stmt->execute($arrDelIds);
            }

            $sql = "DELETE FROM a_chat WHERE (owner_id = :owner_id AND from_id = :from_id) OR (owner_id = :from_id AND from_id = :owner_id)";
            $stmt = $dbh->prepare($sql);
            $stmt->bindParam(':owner_id', $current['owner_id'], PDO::PARAM_INT);
            $stmt->bindParam(':from_id', $current['from_id'], PDO::PARAM_INT);
            $stmt->execute();
        }

    } elseif($type === 'deactivate') {

        CIBlockElement::Delete($id);
    }

    /* Кто и когда изменял запись */
    CIBlockElement::SetPropertyValueCode($id, "MODERATOR", $user_id);
    CIBlockElement::SetPropertyValueCode($id, "MODERATE_TIME", date('d.m.Y H:i:s'));

    /* Сбор данных для отрисовки счётчиков */
    $arFilterCntNew = Array("IBLOCK_ID" => 25, "!PROPERTY_WARNING" => "Y", "!PROPERTY_REJECT" => "Y", "!PROPERTY_DEL" => "Y");
    $resCntNew = CIBlockElement::GetList(array(), $arFilterCntNew, Array(), false, Array());
    $result['NEW'] = $resCntNew ? $resCntNew : 0;

    $arFilterCntRej = Array("IBLOCK_ID" => 25, "PROPERTY_REJECT" => "Y");
    $resCntRej = CIBlockElement::GetList(array(), $arFilterCntRej, Array(), false, Array());
    $result['REJECT'] = $resCntRej ? $resCntRej : 0;

    $arFilterCntWarning = Array("IBLOCK_ID" => 25, "PROPERTY_WARNING" => "Y");
    $resCntWarning = CIBlockElement::GetList(array(), $arFilterCntWarning, Array(), false, Array());
    $result['WARNING'] = $resCntWarning ? $resCntWarning : 0;

    $arFilterCntDel = Array("IBLOCK_ID" => 25, "PROPERTY_DEL" => "Y");
    $resCntDel = CIBlockElement::GetList(array(), $arFilterCntDel, Array(), false, Array());
    $result['DEL'] = $resCntDel ? $resCntDel : 0;

    $arFilterCntChat = Array("IBLOCK_ID" => 25, "PROPERTY_CHAT" => "Y");
    $resCntDel = CIBlockElement::GetList(array(), $arFilterCntDel, Array(), false, Array());
    $result['CHAT'] = $resCntDel ? $resCntDel : 0;

    $arFilterCntAll = Array("IBLOCK_ID" => 25);
    $resCntAll = CIBlockElement::GetList(array(), $arFilterCntAll, Array(), false, Array());
    $result['ALL'] = $resCntAll ? $resCntAll : 0;

}

$data = $result ? array("status" => "success", "res" => $result ) : array("status" => "error", 'message' => 'Ошибка обработки запроса.');
die(json_encode($data));
?>