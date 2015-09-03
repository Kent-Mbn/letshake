<?php
    class CommonComponent extends Component {
        
        function generateAuthToken($userId) {
            return base64_encode(sprintf("%s-%s%s", $userId, time(), substr(md5(rand()), -9)));
        }
        
        function getLinkAvatarFacebook($facebookId) {
            return sprintf("https://graph.facebook.com/%s/picture?type=normal", $facebookId);
        }
        
        function getLinkCountryFlag($country_code) {
            return FULL_BASE_URL."/img/flags/$country_code.png";
        }
    }
?>