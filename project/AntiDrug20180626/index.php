<?php
// require dirname(dirname(dirname(__FILE__))) . '/application/controllers/activity.php';


class MYindex(){
   index2();
    function index2(){
//         $activityTimeList = array(
//     //    //                                    0 => array(
//     //    //                                            's' => '2018-06-26 10:00:00',
//     ////                                            'e' => '2018-06-26 23:59:59'
//     ////                                        ),
//             0 => array(
//                     's' => '2018-06-22 13:24:00',
//                     'e' => '2018-06-22 13:29:59'
//                 ),
//             1 => array(
//                     's' => '2018-06-27 10:00:00',
//                     'e' => '2018-06-27 23:59:59'
//                 ),
//             2 => array(
//                     's' => '2018-06-28 10:00:00',
//                     'e' => '2018-06-28 23:59:59'
//                 )
//         );
//         $canGo1 = false;
//         foreach ($activityTimeList as $av) {
//             if (time() > strtotime($av['s']) && time() < strtotime($av['e'])) {
//                 $canGo1 = true;
//                 break;
//             }
//         }
//         if (!$canGo1) {
//             if (time() > strtotime('2018-06-28 23:59:59')) {
//                 header('Location: http://wx.wuliqinggu.com/project/AntiDrug20180626/over2.html');
//                 exit();
//             }
//             header('Location: http://wx.wuliqinggu.com/project/AntiDrug20180626/over.html');
//             exit();
//         }


         $this->load->library('Activity');
         // $activity = new Activity();

          // $canGo2 = false;
          // $redpacks = $this->Activity->getCurrentRedPack();
          // foreach ($redpacks as $rv) {
          //     if ($rv > 0) {
          //         $canGo2 = true;
          //         break;
          //     }
          // }
          // if (!$canGo2) {
          //     header('Location: http://wx.wuliqinggu.com/project/AntiDrug20180626/over.html');
          //     exit();
          // }
         html();
    }



    function html(){
        echo "<!DOCTYPE html>\n";
        echo "<html lang=\"en\">\n";
        echo "<head>\n";
        echo "<meta charset=\"UTF-8\">\n";
        echo "<meta name=\"viewport\" content=\"width=device-width,user-scalable=no\">\n";
        echo "<meta http-equiv=\"Cache-Control\" content=\"no-cache, no-store, must-revalidate\" />\n";
        echo "<meta http-equiv=\"Pragma\" content=\"no-cache\" />\n";
        echo "<meta http-equiv=\"Expires\" content=\"0\" />\n";
        echo "<link rel=\"stylesheet\" href=\"css/public.css\">\n";
        echo "<link rel=\"stylesheet\" href=\"css/index.css\">\n";
        echo "<title>“禁毒王者”竞答活动</title>\n";
        echo "</head>\n";
        echo "<body>\n";
        echo "<div class=\"container\">\n";
        echo "  <div class=\"loading\"></div>\n";
        echo "  <!-- 首页 -->\n";
        echo "  <div class=\"page0 page\" style=\"display:none;\">\n";
        echo "    <div class=\"page_bg\"></div>\n";
        echo "    <div class=\"page_top\"></div>\n";
        echo "    <div class=\"page0_top\"></div>\n";
        echo "    <div class=\"page0_title\"></div>\n";
        echo "    <div class=\"page0_icon2\"></div>\n";
        echo "    <div class=\"page0_icon1\"></div>\n";
        echo "    <div class=\"page0_buttonWrap\">\n";
        echo "      <div class=\"rule button\">\n";
        echo "        <i class=\"button_word word_cansai\"></i>\n";
        echo "      </div>\n";
        echo "      <div class=\"moveGame button\">\n";
        echo "        <i class=\"button_word word_canyu\"></i>\n";
        echo "      </div>\n";
        echo "    </div>\n";
        echo "    <div class=\"page0_bottom\"></div>\n";
        echo "  </div>\n";
        echo "  <!-- 进入答题页 -->\n";
        echo "  <div class=\"page1 page\" style=\"display:none;\">\n";
        echo "    <div class=\"page_bg\"></div>\n";
        echo "    <div class=\"page0_title\"></div>\n";
        echo "    <div class=\"page1_button moveGame2\"></div>\n";
        echo "    <div class=\"page0_bottom\"></div>\n";
        echo "  </div>\n";
        echo "  <!-- 进入答题页 -->\n";
        echo "  <div class=\"page2 page\" style=\"display:none;\">\n";
        echo "    <div class=\"page_bg\"></div>\n";
        echo "    <ul class=\"checkPoint\">\n";
        echo "      <li class=\"checkPointLi clearfix\">\n";
        echo "        <div class=\"checkPointSub checkPointSub1\">\n";
        echo "          <div class=\"wrap_icon\">\n";
        echo "            <div class=\"this_state1 this_state\"></div>\n";
        echo "          </div>\n";
        echo "          <div class=\"wrap_a_bian wrap_a_left\"></div>\n";
        echo "          <div class=\"star_wrap clearfix\">\n";
        echo "            <div class=\"star effect1\"></div>\n";
        echo "            <div class=\"star\"></div>\n";
        echo "            <div class=\"star\"></div>\n";
        echo "            <div class=\"star\"></div>\n";
        echo "            <div class=\"star\"></div>\n";
        echo "          </div>\n";
        echo "          <div class=\"wrap_a_bian wrap_a_right\"></div>\n";
        echo "        </div>\n";
        echo "        <div class=\"redPackage\">\n";
        echo "          <p class=\"p1\">剩余</p>\n";
        echo "          <p class=\"p2\"><span class=\"redNum\">1000</span>个</p>\n";
        echo "        </div>\n";
        echo "      </li>\n";
        echo "      <li class=\"checkPointLi clearfix\">\n";
        echo "        <div class=\"checkPointSub checkPointSub2\">\n";
        echo "          <div class=\"wrap_icon\">\n";
        echo "            <div class=\"this_state1 this_state\"></div>\n";
        echo "          </div>\n";
        echo "          <div class=\"wrap_a_bian wrap_a_left\"></div>\n";
        echo "          <div class=\"star_wrap clearfix\">\n";
        echo "            <div class=\"star on\"></div>\n";
        echo "            <div class=\"star\"></div>\n";
        echo "            <div class=\"star\"></div>\n";
        echo "            <div class=\"star\"></div>\n";
        echo "            <div class=\"star\"></div>\n";
        echo "          </div>\n";
        echo "          <div class=\"wrap_a_bian wrap_a_right\"></div>\n";
        echo "        </div>\n";
        echo "        <div class=\"redPackage\">\n";
        echo "          <p class=\"p1\">剩余</p>\n";
        echo "          <p class=\"p2\"><span class=\"redNum\">1000</span>个</p>\n";
        echo "        </div>\n";
        echo "      </li>\n";
        echo "      <li class=\"checkPointLi clearfix\">\n";
        echo "        <div class=\"checkPointSub checkPointSub3\">\n";
        echo "          <div class=\"wrap_icon\">\n";
        echo "            <div class=\"this_state1 this_state\"></div>\n";
        echo "          </div>\n";
        echo "          <div class=\"wrap_a_bian wrap_a_left\"></div>\n";
        echo "          <div class=\"star_wrap clearfix\">\n";
        echo "            <div class=\"star on\"></div>\n";
        echo "            <div class=\"star on\"></div>\n";
        echo "            <div class=\"star\"></div>\n";
        echo "            <div class=\"star\"></div>\n";
        echo "            <div class=\"star\"></div>\n";
        echo "          </div>\n";
        echo "          <div class=\"wrap_a_bian wrap_a_right\"></div>\n";
        echo "        </div>\n";
        echo "        <div class=\"redPackage\">\n";
        echo "          <p class=\"p1\">剩余</p>\n";
        echo "          <p class=\"p2\"><span class=\"redNum\">1000</span>个</p>\n";
        echo "        </div>\n";
        echo "      </li>\n";
        echo "      <li class=\"checkPointLi clearfix\">\n";
        echo "        <div class=\"checkPointSub checkPointSub4\">\n";
        echo "          <div class=\"wrap_icon\">\n";
        echo "            <div class=\"this_state1 this_state\"></div>\n";
        echo "          </div>\n";
        echo "          <div class=\"wrap_a_bian wrap_a_left\"></div>\n";
        echo "          <div class=\"star_wrap clearfix\">\n";
        echo "            <div class=\"star on\"></div>\n";
        echo "            <div class=\"star on\"></div>\n";
        echo "            <div class=\"star on\"></div>\n";
        echo "            <div class=\"star\"></div>\n";
        echo "            <div class=\"star\"></div>\n";
        echo "          </div>\n";
        echo "          <div class=\"wrap_a_bian wrap_a_right\"></div>\n";
        echo "        </div>\n";
        echo "        <div class=\"redPackage\">\n";
        echo "          <p class=\"p1\">剩余</p>\n";
        echo "          <p class=\"p2\"><span class=\"redNum\">1000</span>个</p>\n";
        echo "        </div>\n";
        echo "      </li>\n";
        echo "      <li class=\"checkPointLi clearfix\">\n";
        echo "        <div class=\"checkPointSub checkPointSub5\">\n";
        echo "          <div class=\"wrap_icon\">\n";
        echo "            <div class=\"this_state1 this_state\"></div>\n";
        echo "          </div>\n";
        echo "          <div class=\"wrap_a_bian wrap_a_left\"></div>\n";
        echo "          <div class=\"star_wrap clearfix\">\n";
        echo "            <div class=\"star on\"></div>\n";
        echo "            <div class=\"star on\"></div>\n";
        echo "            <div class=\"star on\"></div>\n";
        echo "            <div class=\"star on\"></div>\n";
        echo "            <div class=\"star\"></div>\n";
        echo "          </div>\n";
        echo "          <div class=\"wrap_a_bian wrap_a_right\"></div>\n";
        echo "        </div>\n";
        echo "        <div class=\"redPackage\">\n";
        echo "          <p class=\"p1\">剩余</p>\n";
        echo "          <p class=\"p2\"><span class=\"redNum\">1000</span><span class=\"s1\">个</span></p>\n";
        echo "        </div>\n";
        echo "      </li>\n";
        echo "    </ul>\n";
        echo "    <div class=\"startQuestion\"></div>\n";
        echo "    <div class=\"page0_bottom\"></div>\n";
        echo "  </div>\n";
        echo "  <!-- 答题页 -->\n";
        echo "  <div class=\"page3 page\" style=\"display:none;\">\n";
        echo "    <div class=\"page_bg\"></div>\n";
        echo "    <div class=\"wrap_page3\">\n";
        echo "      <div class=\"question rz3\"><span class=\"xulie\"></span><span class=\"state\"></span><span class=\"content\"></span></div>\n";
        echo "      <ul class=\"answer rz3\">\n";
        echo "        <li class=\"answerSub clearfix\">\n";
        echo "          <div class=\"select on\"></div>\n";
        echo "          <div class=\"content\"></div>\n";
        echo "        </li>\n";
        echo "        \n";
        echo "        <li class=\"answerSub clearfix\">\n";
        echo "          <div class=\"select on\"></div>\n";
        echo "          <div class=\"content\"></div>\n";
        echo "        </li>\n";
        echo "          <li class=\"answerSub clearfix\">\n";
        echo "          <div class=\"select on\"></div>\n";
        echo "          <div class=\"content\"></div>\n";
        echo "        </li>\n";
        echo "          <li class=\"answerSub clearfix\">\n";
        echo "          <div class=\"select on\"></div>\n";
        echo "          <div class=\"content\"></div>\n";
        echo "        </li>\n";
        echo "      </ul>\n";
        echo "      <div class=\"rz3 button countDown_wrap\">\n";
        echo "        <div class=\"countDown\"></div>\n";
        echo "      </div>\n";
        echo "      <div class=\"rz3 tips_wrap\">\n";
        echo "        <span class=\"cp_name\"></span>\n";
        echo "        <span>\n";
        echo "          第<span class=\"currentsQusetion\"></span>题/共10题\n";
        echo "        </span>\n";
        echo "        <span class=\"score\"></span>\n";
        echo "      </div>\n";
        echo "    </div>\n";
        echo "    <div class=\"page0_bottom\"></div>\n";
        echo "  </div>\n";
        echo "  <!-- 结算页面 -->\n";
        echo "  <div class=\"page4 page\" style=\"display:none;\">\n";
        echo "    <div class=\"page_bg\"></div>\n";
        echo "    <div class=\"page0_top\"></div>\n";
        echo "    <div class=\"success\">\n";
        echo "      <div class=\"page4_title\">\n";
        echo "        <div class=\"title_word\"></div>\n";
        echo "      </div>\n";
        echo "      <div class=\"page4_icon\"></div>\n";
        echo "      <div class=\"font_wrap\">\n";
        echo "        <p class=\"p4\">恭喜！</p>\n";
        echo "        <p class=\"p1\">您的分数为<span class=\"page4_score\"></span></p>\n";
        echo "        <p class=\"p2\">您已成功闯关<span class=\"page4_index\">一</span>星关卡</p>\n";
        echo "        <p class=\"p3\">乘胜追击，继续闯关吧</p>\n";
        echo "      </div>\n";
        echo "      <div class=\"button_wrap clearfix\">\n";
        echo "        <div class=\"continueGame button\">\n";
        echo "          <i class=\"button_word word_continue\"></i>\n";
        echo "        </div>\n";
        echo "        <div class=\"endGame button\">\n";
        echo "          <i class=\"button_word word_getAward\"></i>\n";
        echo "        </div>\n";
        echo "      </div>\n";
        echo "    </div>\n";
        echo "    <div class=\"failed\">\n";
        echo "      <div class=\"page4_title\">\n";
        echo "        <div class=\"title_word\"></div>\n";
        echo "      </div>\n";
        echo "      <div class=\"page4_icon\"></div>\n";
        echo "      <div class=\"font_wrap\">\n";
        echo "        <p class=\"p4\">很遗憾！</p>\n";
        echo "        <p class=\"p1\">您的分数为<span class=\"page4_score\"></span></p>\n";
        echo "        <p class=\"p2\">您此关闯关失败</p>\n";
        echo "        <p class=\"p3\">请再接再厉，重新挑战一次吧</p>\n";
        echo "       </div>\n";
        echo "      <div class=\"button_wrap clearfix\">\n";
        echo "        <div class=\"continueGame button\">\n";
        echo "          <i class=\"button_word word_again\"></i>\n";
        echo "        </div>\n";
        echo "        <div class=\"endGame button\">\n";
        echo "          <i class=\"button_word word_end\"></i>\n";
        echo "        </div>\n";
        echo "      </div>\n";
        echo "    </div>\n";
        echo "    <div class=\"page0_bottom\"></div>\n";
        echo "  </div>\n";
        echo "  <!-- 转圈圈 -->\n";
        echo "  <div class=\"popup popup_error error_tips\">\n";
        echo "    <div class=\"popup_main\">\n";
        echo "        <div class=\"popup_icon1\"></div>\n";
        echo "        <p class=\"p1 error_aa\">很遗憾回答错误!</p>\n";
        echo "        <div class=\"popup_line1\"></div>\n";
        echo "        <p class=\"p2\"><span class=\"s1\">正确答案：</span><span class=\"xuanxiang\"></span><span class=\"content\"></span></p>\n";
        echo "        <p class=\"p3\"><span class=\"s1\">正确答案：</span></p>\n";
        echo "        <div class=\"popup_scroll\">\n";
        echo "          <p class=\"p4\"><span class=\"xuanxiang\"></span><span class=\"content\"></span></p>\n";
        echo "          <p class=\"p4\"><span class=\"xuanxiang\"></span><span class=\"content\"></span></p>\n";
        echo "          <p class=\"p4\"><span class=\"xuanxiang\"></span><span class=\"content\"></span></p>\n";
        echo "          <p class=\"p4\"><span class=\"xuanxiang\"></span><span class=\"content\"></span></p>\n";
        echo "        </div>\n";
        echo "      <div class=\"down\"></div>\n";
        echo "      <div class=\"button next\">\n";
        echo "        <i class=\"button_word word_next\"></i>\n";
        echo "      </div>\n";
        echo "    </div>\n";
        echo "  </div>\n";
        echo "  <div class=\"popup popup_success\">\n";
        echo "    <div class=\"popup_main\">\n";
        echo "    </div>\n";
        echo "  </div>\n";
        echo "  <div class=\"popup popup_rule\">\n";
        echo "    <div class=\"popup_main\">\n";
        echo "      <div class=\"close\"></div>\n";
        echo "      <p class=\"rule_title\">竞赛规则</p>\n";
        echo "      <div class=\"rule_line\"></div>\n";
        echo "      <div class=\"popup_rule_scroll\">\n";
        echo "        <p class=\"p1\"><span class=\"s2\">1. </span>竞赛设<span class=\"s1\">青铜奖、白银奖、黄金奖、铂金奖和钻石奖</span>五级奖励，每级各有<span class=\"s1\">10</span>题，答对<span class=\"s1\">9</span>题闯关成功。</p>\n";
        echo "        <p class=\"p1\"><span class=\"s2\">2. </span>每级奖励均有数量限制，奖完即止,一个微信号每天只能领取一个答题奖励。</p>\n";
        echo "        <p class=\"p1\"><span class=\"s2\">3. </span>闯闯关成功，可选择领取本级别奖励或者继续挑战下一级别，挑战下一级别失败，退回上一级别。\n";
        echo "例：闯过黄金奖，选择冲击铂金奖，失败后要退回白银奖选择“重新挑战“或“终止挑战”。</p>\n";
        echo "      </div>\n";
        echo "    </div>\n";
        echo "  </div>\n";
        echo "  <div class=\"popup popup_tips popup_tip\">\n";
        echo "    <div class=\"popup_main\">\n";
        echo "      <div class=\"close\"></div>\n";
        echo "      <p class=\"p1\">请点击《平安静安》公众号下方菜单栏中《领取红包》按钮，领取您的大红包吧</p>\n";
        echo "    </div>\n";
        echo "  </div>\n";
        echo "  <div class=\"popup popup_tips2 popup_tip\">\n";
        echo "    <div class=\"popup_main\">\n";
        echo "      <div class=\"close\"></div>\n";
        echo "      <p class=\"p1\">红包以发放完毕</p>\n";
        echo "    </div>\n";
        echo "  </div>\n";
        echo "</div>\n";
        echo "</body>\n";
        echo "<script src=\"js/load.js\"></script>\n";
        echo "<script src=\"http://apps.bdimg.com/libs/jquery/1.8.1/jquery.min.js\"></script>\n";
        echo "<script src=\"js/adapt.js\"></script>\n";
        echo "<script src=\"js/main.js\"></script>\n";
        echo "</html>\n";
    }
//
//}
}
   

?>