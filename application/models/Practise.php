<?php
use Elasticsearch\Client;

class PractiseModel
{
    public static $levelList = array(
        "75" => "菜鸟",
        "40" => "学民",
        "15" => "学霸",
        "5" => "学神",
        "0" => "学魔"
    );

    public static $no2letter = array(
        0 => "空",
        1 => "A",
        2 => "B",
        3 => "C",
        4 => "D",
    );

    /**
     * 视频所属练习题
     */
    public function getVideoBelongPractise($videoId, $courseId)
    {
        $tblCoursePractise = new DB_Haodu_CoursePractise();
        return $tblCoursePractise -> scalar("*", "where video_id={$videoId} and course_id={$courseId}");
    }

    
    /**
     * 用户是否做过练习题
     */
    public function isUserPractiseFinished($uid, $sectionId)
    {        
        $tblUserPractise = new DB_Haodu_UserPractise();
        return $tblUserPractise->scalar("id", "where uid={$uid} and section_id={$sectionId}");
    }

    /**
     * 错误的seq列表
     */
    public function getUserWrongPractise($uid, $sectionId)
    {
        $tblPractiseAnswerHistory = new DB_Haodu_PractiseAnswerHistory();
        $wrongList = $tblPractiseAnswerHistory -> fetchAll("*", "where uid={$uid} and section_id={$sectionId} and (option_id=0 or option_id!=answer_id)", "order by id asc");
        if (!$wrongList) {
            return false;
        }
        
        $wrongPractiseIds = array();
        foreach ($wrongList as $wrong) {
            $wrongPractiseIds[] = $wrong['practise_id'];
        }

        $tblCoursePractise = new DB_Haodu_CoursePractise();
        $practiseList = $tblCoursePractise -> fetchAll("practise_id, seq", "where section_id={$sectionId}", "order by seq asc");
        $wrongSeqList = array();
        foreach ($practiseList as $practise) {
            if (in_array($practise['practise_id'], $wrongPractiseIds)) {
                $wrongSeqList[] = $practise['seq'];
            }
        }

        return $wrongSeqList;
    }


    /**
     * 用户练习题报告
     * 综合排名的计算方式为：
     计算所有人的 正确率*0.7+(最慢的答题时间-我的答题时间)/(最慢的答题时间-最快的答题时间)*0.3
并按照计算结果排序得到我的综合排名
     */
    public function getUserPractiseReport($uid, $sectionId)
    {
        $tblUserPractise = new DB_Haodu_UserPractise();
        $userPractise = $tblUserPractise -> scalar("*", "where uid={$uid} and section_id={$sectionId}");
        if (!$userPractise) {
            return false;
        }

        $practiseUserNum = $tblUserPractise -> queryCount("where section_id={$sectionId}");
        $scoreWinUserNum = $tblUserPractise -> queryCount("where correct_num <= {$userPractise['correct_num']} and section_id={$sectionId}");
        $spendTimeWinUserNum = $tblUserPractise->queryCount("where spend_time >= {$userPractise['spend_time']} and section_id={$sectionId}");

        $tblCoursePractise = new DB_Haodu_CoursePractise();
        $sectionPractiseList = $tblCoursePractise -> fetchAll("*","where section_id={$sectionId}");
        $sectionPractiseList = $tblCoursePractise -> kv($sectionPractiseList, "practise_id", "video_id");
        $sectionPractiseNum  = count($sectionPractiseList);
        $userPractise['practise_num'] = $sectionPractiseNum;


        $tblPractiseAnswerHistory = new DB_Haodu_PractiseAnswerHistory();
        $userAnswerHistory = $tblPractiseAnswerHistory -> fetchAll("*", "where uid={$uid} and section_id={$sectionId}", "order by id asc");

        //总的答对数量
        $correctTotal = $tblPractiseAnswerHistory -> queryCount("where section_id={$sectionId} and option_id!=0 and option_id=answer_id");
        //最快最慢的时间
        $max = $tblUserPractise ->scalar("max(spend_time) as spend_time", "where section_id={$sectionId}");
        $min = $tblUserPractise ->scalar("min(spend_time) as spend_time", "where section_id={$sectionId}");

		$rankByScore = $sectionPractiseNum == 0 ? 0 : $userPractise['correct_num']/$sectionPractiseNum*0.7;
		$rankBySpendTime = $max['spend_time']-$userPractise['spend_time'] == 0 || $max['spend_time'] == $min['spend_time'] ? 
            0 : ($max['spend_time']-$userPractise['spend_time'])/($max['spend_time']-$min['spend_time'])*0.3;
        $rankVal = $rankByScore + $rankBySpendTime;

        $where = "where section_id={$sectionId}";
		$where .= $sectionPractiseNum == 0 ? "0" : " and correct_num/{$sectionPractiseNum}*0.7";
		$where .= $max['spend_time']-$userPractise['spend_time'] == 0 ? "0" : "+({$max['spend_time']}-spend_time)/({$max['spend_time']}-{$min['spend_time']})*0.3";
        $where .= ">{$rankVal}";

        $rank = $tblUserPractise ->queryCount($where);
		$rank = $rankByScore == 0 ? $practiseUserNum : $rank+1;


        $userPractise['create_time_fmt'] = date("Y-m-d H:i:s", $userPractise['create_time']);
        //$userPractise['correct_rate'] = $practiseUserNum  == 0 ? 0 : "{$userPractise['correct_num']}/{$practiseUserNum}";
        $userPractise['correct_rate'] = "{$userPractise['correct_num']}/{$sectionPractiseNum}";
        $userPractise['spend_time_fmt'] = sprintf("%02d",floor($userPractise['spend_time']/60)) . ":" . sprintf("%02d",($userPractise['spend_time'] % 60));
        $userPractise['score_win_rate'] = round($scoreWinUserNum/$practiseUserNum, 2) * 100 . "%";
        $userPractise['spendtime_win_rate'] = round($spendTimeWinUserNum/$practiseUserNum, 2) * 100 ."%";
        $userPractise['total_rank'] = "{$rank}/{$practiseUserNum}";
        
        //封号的设置方式为：
        //排名百分比按区间向下取整，例如如果有2人，一个排名的区间在[0,0.5]，一个排名的区间在[0.5,1]。那么排名百分比分别取0%和50%
        //-排名 0-5% 学魔
        //-排名 5%-15% 学神
        //-排名 15%-40% 学霸
        //-排名 40%-75% 学民
        //-排名 75%-90% 菜鸟
        //-排名 90%-100% 学沫
        foreach (self::$levelList as $k=>$level) {
            if (100*($rank-1)/$practiseUserNum >= intval($k)) {
                $userPractise['rank_level'] = $level;
                break;
            }
        }       

        $seq = 1;
        foreach ($userAnswerHistory as $answer) {
            $correct = $answer['option_id'] && $answer['answer_id'] == $answer['option_id'] ? 1 : 0;                        
            $letter = chr(320+$answer['answer_id']);
            $userPractise['answer_list'][] = array(
                "id" => $answer["practise_id"], 
                "seq" => $seq, 
                "letter_seq" => $letter, 
                "correct" => $correct,
                "video_id" => isset($sectionPractiseList[$answer["practise_id"]]['video_id']) ? $sectionPractiseList[$answer["practise_id"]]['video_id'] : 0,
            );
            $seq++;
        }

        return $userPractise;
    }

    
    /**
     * 练习题统计
     */
    public function getPractiseStatistic($uid, $practiseId, $sectionId)
    {
        $tblUserPractise = new DB_Haodu_UserPractise();
        $practiseUserNum = $tblUserPractise -> queryCount("where section_id={$sectionId}");
        $userPractise = $tblUserPractise -> scalar("*", "where uid={$uid} and section_id={$sectionId}"); 


        $tblPractiseAnswerHistory = new DB_Haodu_PractiseAnswerHistory();        
        $correctNum = $tblPractiseAnswerHistory -> queryCount("where section_id={$sectionId} and practise_id={$practiseId} and option_id=answer_id and option_id!=0");        
        $userAnswer = $tblPractiseAnswerHistory -> scalar("*", "where uid={$uid} and section_id={$sectionId} and practise_id={$practiseId}");
        $wrong = $tblPractiseAnswerHistory -> query("select count(1) as total, option_id from xdf_practise_answer_history where section_id={$sectionId} and practise_id={$practiseId} and option_id!=0 and option_id!=answer_id group by option_id order by total desc");
        $wrong = $wrong->fetch_array();

        $tblCoursePractise = new DB_Haodu_CoursePractise();
        $sectionPractiseList = $tblCoursePractise -> fetchAll("*","where section_id={$sectionId}");
        $sectionPractiseNum  = count($sectionPractiseList);

        
        $word = "正确答案是%s， 您的答案是%s， 回答%s，作答用时%s．<br/>";
        $word .= "本题共被作答%s次，正确率为%s%%，易错项为%s．";
        $word = sprintf($word, 
                self::$no2letter[$userAnswer['answer_id']], 
                self::$no2letter[$userAnswer['option_id']], 
                $userAnswer['answer_id'] == $userAnswer['option_id'] && $userAnswer['option_id'] != 0 ? "正确" : "错误",
				sprintf("%02d",floor($userPractise['spend_time']/60)) . "分" . sprintf("%02d",($userPractise['spend_time'] % 60)). '秒',
                $practiseUserNum,
                round($correctNum*100/$practiseUserNum),
                self::$no2letter[(int)$wrong['option_id']]
        );

        $statistic = array(
            "answer_id" => self::$no2letter[$userAnswer['answer_id']],
            "option_id" => self::$no2letter[$userAnswer['option_id']],
            "is_correct" => $userAnswer['answer_id']==$userAnswer['option_id'] && $userAnswer['option_id'] != 0 ? 1 : 0,
            "spend_time" => $userPractise['spend_time'],
            "user_total" => $practiseUserNum,
            "correct_rate" => round($correctNum*100/$practiseUserNum) . "%",
            "correct_ratio" => "${userPractise['correct_num']}/{$sectionPractiseNum}",
            "most_wrong" => self::$no2letter[(int)$wrong["option_id"]],
            "word" => $word,
        );

        return $statistic;
    }



    /**
     * 用户练习纪录
     */
    public function insertUserPractise($uid, $courseId, $sectionId, $spendTime, $correctNum)
    {
        $time = time();
        $data = array(
            "uid" => $uid, 
            "course_id" =>$courseId,
            "section_id" =>$sectionId,
            "spend_time" =>$spendTime,
            "create_time" =>$time,
            "correct_num" => $correctNum,
         );
        $tbl = new DB_Haodu_UserPractise();
        return $tbl->insert($data);
    }

    /**
     * 删除旧的记录
     */
    public function deleteAnswer($sectionId, $uid, $practiseId)
    {
        $tbl = new DB_Haodu_PractiseAnswerHistory();
        $tbl->batchDelete("where section_id={$sectionId} and uid={$uid} and practise_id={$practiseId}");
    }

    /**
     * 回答历史
     */
    public function insertAnswer($sectionId, $uid, $practiseId, $optionId, $answerId)
    {
        $time = time();
        $data = array(
            "section_id" => $sectionId,
            "uid" => $uid,
            "practise_id" => $practiseId,
            "option_id" => $optionId,
            "answer_id" => $answerId,
            "create_time" => $time
        );

        $this->deleteAnswer($sectionId, $uid, $practiseId);
        $tbl = new DB_Haodu_PractiseAnswerHistory();
        return $tbl -> insert($data);
    }

    /**
     * 获得节下练习题数量
     */
    public function getSectionPractiseNum($sectionId)
    {
        $tbl = new DB_Haodu_CoursePractise();
        return $tbl -> queryCount("where section_id={$sectionId}");
    }

    /**
     * 练习题
     */
    public function getPractiseBySeq($sectionId, $seq)
    {
        $tblCoursePractise = new DB_Haodu_CoursePractise();
        $practise = $tblCoursePractise -> scalar("id, seq, practise_id, video_id", "where section_id={$sectionId} and seq={$seq}");        
        $practiseInfo  = $this->getPractise($practise['practise_id']);
        if (!$practiseInfo) {
            return false;
        }

        $practiseInfo['video_id'] = $practise['video_id'];
        $practiseInfo['practise_id'] = $practise['practise_id'];
        return $practiseInfo;
    }

    /**
     * 判断答题
     */
    public function check($practiseId, $answerId)
    {
        $practise  = $this->getPractise($practiseId);
        return $answerId!=0 && array_search($practise['nested_node_tree']['node_answer'], self::$no2letter) == $answerId;
    }

    /**
     * 练习答案
     */
    public function getPractiseAnswerId($practiseId)
    {
        $practise  = $this->getPractise($practiseId);
        return is_numeric($practise['nested_node_tree']['node_answer']) 
            ? $practise['nested_node_tree']['node_answer'] 
            : array_search($practise['nested_node_tree']['node_answer'], self::$no2letter);
    }

    /**
     * 节习题列表
     */
    public function getSectionPractiseList($sectionId)
    {
        $tbl = new DB_Haodu_CoursePractise();
        $list = $tbl -> fetchAll("*", "where section_id={$sectionId}", "order by seq asc");
        return $tbl-> kv($list, "id");
    }


    /**
     * 题干
     */
    public function getPractiseSubject($practiseId)
    {
        $practise  = $this->getPractise($practiseId);
        return $practise['nested_node_tree']["node_content"];
    }
    /**
     * 选项
     */
    public function getPractiseOption($practiseId, $optionId = 1)
    {
        $practise  = $this->getPractise($practiseId);
        return $practise['nested_node_tree']['nested_node_children'][$optionId]["node_content"];
    }





    private function getPractise($id)
    {
        $json = '{
                  "query": {
                    "bool": {
                      "must": {
                        "term": {
                          "udo2_item.item_id": "'.$id.'"
                        }
                      }
                    }
                  }
                }';

        try{
            $client = new Elasticsearch\Client(Common_Config::$elastic_config);
            $rs = $client->search(array("index" => 'udo2_index', 'type' => 'udo2_item', 'body' => $json));
        } catch (Exception $ex) {
            return false;
        }
        if($rs['hits']['total'] > 0) {
            $typeId = $rs['hits']['hits'][0]['_source']['type_id'];
            $tblItemType = new DB_Haodu_ItemType();
            $type = $tblItemType -> fetchRow($typeId, "type_name");
            $typeName = isset($type['type_name']) ? $type['type_name'] : "";
            //补充名称
            $rs['hits']['hits'][0]['_source']['type_name'] = isset($rs['hits']['hits'][0]['_source']['type_name']) ? $rs['hits']['hits'][0]['_source']['type_name'] : $typeName;
            $this->filter($rs['hits']['hits'][0]['_source']);
            return $rs['hits']['hits'][0]['_source'];
        }

        return false;
    }

    public function getSubject($nested_content_raw)
    {
        $subject = '';
        foreach ($nested_content_raw as $row) {
            $subject .= $row['content'];
        }
        return $subject;
    }


    private function filter(&$question)
    {
        foreach($question['nested_node_tree']['nested_node_children'] as &$option) {
                $option['node_content'] = preg_replace("/[ABCD]+[\.\．]+/i", "", $option['node_content']);
        }
        unset($option);
    }





}
/**
#题目表数据结构
{
    "_index": "udo_index",
    "_type": "udo_item",
    "_id": "1425633482",
    "_version": 2,
    "_score": 1,
    "_source": {
        "item_id": 1425633482,		//题目ID
        "user_id": 0,			//作者ID
        "sub_id": "2",			//科目ID
        "type_id": "38",		//题目类型ID
        "difficulty": "0",		//难度
        "item_hash": "f6c98fa67299c0204e13ed98dab2d2be",	//验重hash
        "create_time": "2015-03-06 09:18:02",			//发布时间
        "nested_four_level_controls": {
            "province": 0,	//省
            "city": 0,		//市				
            "district": 0,	//区
            "school": 0		//学校
        },
        "exam_type_id": 19,	//考试类型
        "nested_other_controls": {
            "academic_year": 0,		//学年
            "education_type": 0,	//教育类型
            "grade": 0,			//年级
            "semester": 0,		//适用学期
            "month": 0,			//月
            "exam_branch": 0		//考试分科
        },
        "nested_content_raw": [
            {
                "content": "<p>11111111111111</p> "	//题目内容
            },
            {
                "content": "<p>1111111111</p> "
            },
            {
                "content": "<p>11111111111</p> "
            },
            {
                "content": "<p>111111111</p> "
            },
            {
                "content": "<p>1111111</p> "
            }
        ],
        "nested_knows_raw": [],
        "nested_skill_raw": [],
        "nested_node_tree": {
            "node_index": 0,				//节点索引号
            "node_type": 0,				//节点类型
            "node_content": "<p>11111111111111</p> ",	//节点内容
            "node_answer": "<p></p>",			//节点答案
            "node_parse": "<p></p>",			//节点解析
            "node_degree": 0,				//节点难度
            "nested_node_knows": [],			//节点知识点
            "nested_node_skill": [],			//节点解析技巧
            "nested_node_children": [			//子节点
                {
                    "node_index": 1,
                    "node_type": 0,
                    "node_content": "<p>1111111111</p> ",
                    "node_answer": "<p></p>",
                    "node_parse": "<p></p>",
                    "node_degree": 0,
                    "nested_node_knows": [],
                    "nested_node_skill": [],
                    "nested_node_children": []
                },
                {
                    "node_index": 2,
                    "node_type": 0,
                    "node_content": "<p>11111111111</p> ",
                    "node_answer": "<p></p>",
                    "node_parse": "<p></p>",
                    "node_degree": 0,
                    "nested_node_knows": [],
                    "nested_node_skill": [],
                    "nested_node_children": []
                },
                {
                    "node_index": 3,
                    "node_type": 0,
                    "node_content": "<p>111111111</p> ",
                    "node_answer": "<p></p>",
                    "node_parse": "<p></p>",
                    "node_degree": 0,
                    "nested_node_knows": [],
                    "nested_node_skill": [],
                    "nested_node_children": []
                },
                {
                    "node_index": 4,
                    "node_type": 0,
                    "node_content": "<p>1111111</p> ",
                    "node_answer": "<p></p>",
                    "node_parse": "<p></p>",
                    "node_degree": 0,
                    "nested_node_knows": [],
                    "nested_node_skill": [],
                    "nested_node_children": []
                }
            ]
        }
    }
}

#模版表数据结构
{
    "_index": "udo_index",
    "_type": "udo_model",
    "_id": "4",
    "_version": 1,
    "_score": 1,
    "_source": {
        "model_id": 4,				//模版ID
        "model_name": "选择题",			//模版名称
        "user_id": 0,				//作者ID
        "sub_id": "2",				//科目ID
        "type_id": 38,				//类型ID
        "create_time": "2015-03-04 11:27:55",	//发布时间
        "nested_four_level_controls": {
            "province": 0,	//省
            "city": 0,		//市				
            "district": 0,	//区
            "school": 0		//学校
        },
        "exam_type_id": 19,		//考试类型
        "nested_other_controls": {
            "academic_year": 0,		//学年
            "education_type": 0,	//教育类型
            "grade": 0,			//年级
            "semester": 0,		//适用学期
            "month": 0,			//月
            "exam_branch": 0		//考试分科
        },
        "nested_node_tree": {
            "node_index": 0,		//节点索引号
            "node_type": 0,		//节点类型
            "node_content": "<p></p>",	//节点内容
            "node_answer": "<p></p>",	//节点答案
            "node_parse": "<p></p>",	//节点解析
            "node_degree": 0,		//节点难度
            "nested_node_knows": [],	//节点知识点
            "nested_node_skill": [],	//节点解析技巧
            "nested_node_children": [	//子节点
                {
                    "node_index": 1,
                    "node_type": 0,
                    "node_content": "<p></p>",
                    "node_answer": "<p></p>",
                    "node_parse": "<p></p>",
                    "node_degree": 0,
                    "nested_node_knows": [],
                    "nested_node_skill": [],
                    "nested_node_children": []
                },
                {
                    "node_index": 2,
                    "node_type": 0,
                    "node_content": "<p></p>",
                    "node_answer": "<p></p>",
                    "node_parse": "<p></p>",
                    "node_degree": 0,
                    "nested_node_knows": [],
                    "nested_node_skill": [],
                    "nested_node_children": []
                },
                {
                    "node_index": 3,
                    "node_type": 0,
                    "node_content": "<p></p>",
                    "node_answer": "<p></p>",
                    "node_parse": "<p></p>",
                    "node_degree": 0,
                    "nested_node_knows": [],
                    "nested_node_skill": [],
                    "nested_node_children": []
                },
                {
                    "node_index": 4,
                    "node_type": 0,
                    "node_content": "<p></p>",
                    "node_answer": "<p></p>",
                    "node_parse": "<p></p>",
                    "node_degree": 0,
                    "nested_node_knows": [],
                    "nested_node_skill": [],
                    "nested_node_children": []
                }
            ]
        }
    }
}


#作业表数据结构
{
    "_index": "udo_index",
    "_type": "udo_homework",
    "_id": "1426064762",
    "_version": 1,
    "_score": 1,
    "_source": {
        "homework_id": 1426064762,	//作业ID
        "teacher_id": "196",		//教师ID
        "need_feedback": null,		//是否需要反馈
        "item_num": 2,
        "nested_item": [
            {
                "item_id": "1425965557",	//题目ID
                "item_order": 0			//题目排序
            },
            {
                "item_id": "1425793935",
                "item_order": 1
            }
        ],
        "status": 1,				//是否为草稿（1-不是草稿，2-是草稿）
        "create_time": "2015-03-12 18:06:14"	//发布时间
    }
}

#题目表Mapping
public function getItemMapping()
{
	$mapping = array(
		'_source' => array(
			'enabled' => true
		),
		'properties' => array(
			'user_id' => array(
				'type' => 'long',
				'store' => 'yes',
				'precision_step' => "1"
			),
			'item_id' => array(
				'type' => 'long',
				'store' => 'yes',
				'precision_step' => "1"
			),
			'sub_id' => array(
				'type' => 'long',
				'store' => 'yes',
				'precision_step' => "1"
			),
			'type_id' => array(
				'type' => 'long',
				'store' => 'yes',
				'precision_step' => "1"
			),
			'difficulty' => array(
				'type' => 'long',
				'store' => 'yes',
				'precision_step' => "1"
			),
			'create_time' => array(
				'type' => 'date',
				'store' => 'yes',
				'format' => "YYYY-mm-dd HH:mm:ss"
			),
			'exam_type_id' => array(
				'type' => 'long',
				'store' => 'yes',
				'precision_step' => "1"
			),
			'nested_four_level_controls' => array(
				'type' => 'nested'
			),
			'province' => array(
				'type' => 'long',
				'store' => 'yes',
				'precision_step' => "1"
			),
			'city' => array(
				'type' => 'long',
				'store' => 'yes',
				'precision_step' => "1"
			),
			'district' => array(
				'type' => 'long',
				'store' => 'yes',
				'precision_step' => "1"
			),
			'school' => array(
				'type' => 'long',
				'store' => 'yes',
				'precision_step' => "1"
			),
			'nested_other_controls' => array(
				'type' => 'nested'
			),
			'academic_year' => array(
				'type' => 'long',
				'store' => 'yes',
				'precision_step' => "1"
			),
			'education_type' => array(
				'type' => 'long',
				'store' => 'yes',
				'precision_step' => "1"
			),
			'grade' => array(
				'type' => 'long',
				'store' => 'yes',
				'precision_step' => "1"
			),
			'semester' => array(
				'type' => 'long',
				'store' => 'yes',
				'precision_step' => "1"
			),
			'month' => array(
				'type' => 'long',
				'store' => 'yes',
				'precision_step' => "1"
			),
			'exam_branch' => array(
				'type' => 'long',
				'store' => 'yes',
				'precision_step' => "1"
			),
			'nested_node_tree' => array(
				'type' => 'nested'
			),
			'node_index' => array(
				'type' => 'long',
				'store' => 'yes',
				'precision_step' => "1"
			),
			'node_type' => array(
				'type' => 'long',
				'store' => 'yes',
				'precision_step' => "1"
			),
			'node_content' => array(
				'type' => 'string',
				'store' => 'no',
				'index' => "analyzed"
			),
			'node_answer' => array(
				'type' => 'string',
				'store' => 'no',
				'index' => "analyzed"
			),
			'node_parse' => array(
				'type' => 'string',
				'store' => 'no',
				'index' => "analyzed"
			),
			'nested_node_knows' => array(
				'type' => 'nested'
			),
			'know_id' => array(
				'type' => 'long',
				'store' => 'yes',
				'precision_step' => "1"
			),
			'know_name' => array(
				'type' => 'string',
				'store' => 'no',
				'index' => "analyzed"
			),
			'nested_node_skill' => array(
				'type' => 'nested'
			),
			'skill_id' => array(
				'type' => 'long',
				'store' => 'yes',
				'precision_step' => "1"
			),
			'skill_name' => array(
				'type' => 'string',
				'store' => 'no',
				'index' => "analyzed"
			),
			'nested_node_children' => array(
				'type' => 'nested'
			),
			'nested_knows_raw' => array(
				'type' => 'nested'
			),
			'nested_skill_raw' => array(
				'type' => 'nested'
			),
			'nested_content_raw' => array(
				'type' => 'nested'
			),
			'content' => array(
				'type' => 'string',
				'store' => 'no',
				'index' => "analyzed"
			),
			'item_raw' => array(
				'type' => 'string',
				'store' => 'no',
				'index' => "analyzed"
			),
		)
	);

	return $mapping;
}

#模版表Mapping
public function getModelMapping()
{
	$mapping = array(
		'_source' => array(
			'enabled' => true
		),
		'properties' => array(
			'user_id' => array(
				'type' => 'long',
				'store' => 'yes',
				'precision_step' => "1"
			),
			'model_id' => array(
				'type' => 'long',
				'store' => 'yes',
				'precision_step' => "1"
			),
			'model_name' => array(
				'type' => 'string',
				'store' => 'no',
				'index' => "analyzed"
			),
			'sub_id' => array(
				'type' => 'long',
				'store' => 'yes',
				'precision_step' => "1"
			),
			'type_id' => array(
				'type' => 'long',
				'store' => 'yes',
				'precision_step' => "1"
			),
			'create_time' => array(
				'type' => 'date',
				'store' => 'yes',
				'format' => "YYYY-mm-dd HH:mm:ss"
			),
			'exam_type_id' => array(
				'type' => 'long',
				'store' => 'yes',
				'precision_step' => "1"
			),
			'nested_four_level_controls' => array(
				'type' => 'nested'
			),
			'province' => array(
				'type' => 'long',
				'store' => 'yes',
				'precision_step' => "1"
			),
			'city' => array(
				'type' => 'long',
				'store' => 'yes',
				'precision_step' => "1"
			),
			'district' => array(
				'type' => 'long',
				'store' => 'yes',
				'precision_step' => "1"
			),
			'school' => array(
				'type' => 'long',
				'store' => 'yes',
				'precision_step' => "1"
			),
			'nested_other_controls' => array(
				'type' => 'nested'
			),
			'academic_year' => array(
				'type' => 'long',
				'store' => 'yes',
				'precision_step' => "1"
			),
			'education_type' => array(
				'type' => 'long',
				'store' => 'yes',
				'precision_step' => "1"
			),
			'grade' => array(
				'type' => 'long',
				'store' => 'yes',
				'precision_step' => "1"
			),
			'semester' => array(
				'type' => 'long',
				'store' => 'yes',
				'precision_step' => "1"
			),
			'month' => array(
				'type' => 'long',
				'store' => 'yes',
				'precision_step' => "1"
			),
			'exam_branch' => array(
				'type' => 'long',
				'store' => 'yes',
				'precision_step' => "1"
			),
			'nested_node_tree' => array(
				'type' => 'nested'
			),
			'node_index' => array(
				'type' => 'long',
				'store' => 'yes',
				'precision_step' => "1"
			),
			'node_type' => array(
				'type' => 'long',
				'store' => 'yes',
				'precision_step' => "1"
			),
			'node_content' => array(
				'type' => 'string',
				'store' => 'no',
				'index' => "analyzed"
			),
			'node_answer' => array(
				'type' => 'string',
				'store' => 'no',
				'index' => "analyzed"
			),
			'node_parse' => array(
				'type' => 'string',
				'store' => 'no',
				'index' => "analyzed"
			),
			'nested_node_knows' => array(
				'type' => 'nested'
			),
			'know_id' => array(
				'type' => 'long',
				'store' => 'yes',
				'precision_step' => "1"
			),
			'know_name' => array(
				'type' => 'string',
				'store' => 'no',
				'index' => "analyzed"
			),
			'nested_node_skill' => array(
				'type' => 'nested'
			),
			'skill_id' => array(
				'type' => 'long',
				'store' => 'yes',
				'precision_step' => "1"
			),
			'skill_name' => array(
				'type' => 'string',
				'store' => 'no',
				'index' => "analyzed"
			),
			'nested_node_children' => array(
				'type' => 'nested'
			),

		)
	);

	return $mapping;
}

#作业表Mapping
public function getHomeworkMapping()
{
	$mapping = array(
		'_source' => array(
			'enabled' => true
		),
		'properties' => array(
			'homework_id' => array(
				'type' => 'long',
				'store' => 'yes',
				'precision_step' => "1"
			),
			'teacher_id' => array(
				'type' => 'long',
				'store' => 'yes',
				'precision_step' => "1"
			),
			'need_feedback' => array(
				'type' => 'long',
				'store' => 'yes',
				'precision_step' => "1"
			),
			'publish_time' => array(
				'type' => 'date',
				'store' => 'yes',
				'format' => "YYYY-mm-dd HH:mm:ss"
			),
			'publish_type' => array(
				'type' => 'long',
				'store' => 'yes',
				'precision_step' => "1"
			),
			'create_time' => array(
				'type' => 'date',
				'store' => 'yes',
				'format' => "YYYY-mm-dd HH:mm:ss"
			),
			'nested_item' => array(
				'type' => 'nested'
			),
			'item_id' => array(
				'type' => 'long',
				'store' => 'yes',
				'precision_step' => "1"
			),
			'item_order' => array(
				'type' => 'long',
				'store' => 'yes',
				'precision_step' => "1"
			),
		)
	);
	return $mapping;
}
*/
