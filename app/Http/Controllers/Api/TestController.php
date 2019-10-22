<?php

namespace App\Http\Controllers\Api;

use App\Models\Game\GameUser;
use JWTAuth;
use JWTFactory;

class TestController extends CommonController
{
    public function test()
    {
        for ($i = 201; $i <= 514; $i++) {
            $filePath = '/data/The_Data_For/玩家信息记录表(' . $i . ').txt';

            $content = file_get_contents($filePath);
            $content = json_decode($content, true);

            unset($content[0]);
            foreach ($content as $key => $val) {
                if ($val[2] == '--') continue;

                $insertData['id'] = $val[0];
                $insertData['account'] = $val[1];
                $insertData['phone_num'] = $val[2];
                $insertData['platform_info'] = $val[4];
                $insertData['game_type'] = $val[5];
                $insertData['reg_ip'] = $val[6];
                $insertData['reg_date'] = $val[3];

                GameUser::query()->insert($insertData);
            }
        }
    }

}
