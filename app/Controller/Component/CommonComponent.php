<?php
    class CommonComponent extends Component {
        function generateAuthToken($userId) {
               return base64_encode(sprintf("%s-%s%s", $userId, time(), substr(md5(rand()), -9)));
        }
    }
?>