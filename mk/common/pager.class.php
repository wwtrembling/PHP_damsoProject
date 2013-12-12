<?
class pager {
        // 기본 입력값
        var $allRows; //총레코드수
        var $showRows; // 한페이지에 보여질 레코드수
        var $curPage; // 현재페이지(0번부터시작)

        // 옵션
        var $showPages=10; //페이지갯수
        var $queryName="page"; //쿼리로 넘어갈 페이지 변수 이름(한페이지에 두개 이상의 페이징이 있을때를 위해서...)
        var $actionPage; //액션페이지

        var $subQuery = array(); //서브쿼리들 addQuery함수 사용해서 추가

        var $tags_prev = "<"; //이전 몇페이지
        var $tags_next = ">"; //다음 몇페이지
        var $tags_begin = "<<"; //처음 페이지
        var $tags_end = ">>"; //마지막 페이지
        var $tags_split2 = "&nbsp;"; // begin과 prev , next와 end 분리자
        var $tags_split = "&nbsp;"; //페이지 분리자
        var $tags_current_index="{index}"; //현재 페이지, {index} 가 현재페이지를 나타냄
        var $tags_index="{index}"; //페이지,  {index} 가 현재페이지를 나타냄

        var $style_a_id = ""; //링크 스타일 아이디

        function pager($curPage, $allRows, $showRows=10)//현재페이지 , 전체개수, 한페이지에 보여줄개수
        {
                $this->curPage = $curPage;
                $this->allRows = $allRows;
                $this->showRows = $showRows;
                $this->actionPage = $_SERVER['PHP_SELF'];
        }

        function addQuery($key, $value)
        {
               $this->subQuery[$key] = $value;
        }

        function stroke()
        {
				$a_link=null;
                 // 기본 필요한 내용
                 foreach( $this->subQuery as $key=>$value) {
                         $a_link .= "&".$key."=".$value;
                 }
                 $current_page = $this->curPage+1; //페이지넘버
                 $list_per_page = $this->showRows; //페이지당 보여질 데이타
                 $all_data = $this->allRows; //총데이타
                 $showPages = $this->showPages;
                 $queryName = $this->queryName;
                 $actionPage = $this->actionPage;
                 $tags_prev = $this->tags_prev;
                 $tags_next = $this->tags_next;
                 $tags_split = $this->tags_split;
                 $tags_split2 = $this->tags_split2;
                 $tags_current_index = $this->tags_current_index;
                 $tags_index = $this->tags_index;
                 $tags_begin = $this->tags_begin;
                 $tags_end = $this->tags_end;

                 $remainder=$all_data % $list_per_page; // 페이징카운트후 나머지
                 $page_count=($all_data - $remainder) / $list_per_page; //총페이지수

                 if ($remainder) $page_count++; //나머지가 있으면 총페이지수 1증가

                 $page_range1=$current_page - ($current_page % $showPages);

                 if (($current_page % $showPages) == 0)
                 {
                  $page_range1 -= ($showPages-1);
                 }
                 else
                 {
                  $page_range1++;
                 }

                 $page_range2=$page_range1 + $showPages - 1;

                 // 처음으로
                 if ( $page_range1 > $showPages) {
                    echo "<a href='{$actionPage}?{$queryName}=0{$a_link}' ";
                    if ( !empty($this->style_a_id) ) echo "class='{$this->style_a_id}'";
                    echo ">{$tags_begin}</a>";

                    echo "{$tags_split2}";
                 }
                 // 이전
                 if ($page_range1 > $showPages)
                 {
                                         //$prev_page=$page_range1 - $showPages;
                                         $prev_page=$page_range1 - 1;
                                         echo "<a href='{$actionPage}?{$queryName}=".($prev_page-1)."{$a_link}' ";
                                         if ( !empty($this->style_a_id) ) echo "class='{$this->style_a_id}'";
                                         echo ">{$tags_prev}</a>";
                 }

                 for ($i=$page_range1; $i <= $page_count && $i<=$page_range2; $i++) {
                          if ($current_page == $i || (!$current_page && $i==1)) {
                                                      echo $tags_split.str_replace("{index}",$i,$tags_current_index);
                          }
                          else
                          {
                                                  echo "{$tags_split}<a href='{$actionPage}?{$queryName}=".($i-1)."{$a_link}' ";
                                                           if ( !empty($this->style_a_id) ) echo "class='{$this->style_a_id}'";
                                                           echo ">".str_replace("{index}",$i,$tags_index)."</a>";
                          }
                 }

                 if ( $all_data != 0 ) echo "{$tags_split}";

                 // 다음
                 if ($i <= $page_count)
                 {
                                         $next_page=$i;
                                         echo "<a href='{$actionPage}?{$queryName}=".($next_page-1)."{$a_link}' ";
                                         if ( !empty($this->style_a_id)) echo "class='{$this->style_a_id}'";
                                         echo ">{$tags_next}</a>";
                 }

                 if ( $page_range2 < $page_count ) {
                         echo "{$tags_split2}";

                        echo "<a href='{$actionPage}?{$queryName}=".($page_count-1)."{$a_link}' ";
                        if ( !empty($this->style_a_id)) echo "class='{$this->style_a_id}'";
                        echo ">{$tags_end}</a>";
                 }
        }
		

		 function vstroke()
        {
                 // 기본 필요한 내용
				 $reresult = "";
                 foreach( $this->subQuery as $key=>$value) {
                         $a_link .= "&".$key."=".$value;
                 }
                 $current_page = $this->curPage+1; //페이지넘버
                 $list_per_page = $this->showRows; //페이지당 보여질 데이타
                 $all_data = $this->allRows; //총데이타
                 $showPages = $this->showPages;
                 $queryName = $this->queryName;
                 $actionPage = $this->actionPage;
                 $tags_prev = $this->tags_prev;
                 $tags_next = $this->tags_next;
                 $tags_split = $this->tags_split;
                 $tags_split2 = $this->tags_split2;
                 $tags_current_index = $this->tags_current_index;
                 $tags_index = $this->tags_index;
                 $tags_begin = $this->tags_begin;
                 $tags_end = $this->tags_end;

                 $remainder=$all_data % $list_per_page; // 페이징카운트후 나머지
                 $page_count=($all_data - $remainder) / $list_per_page; //총페이지수

                 if ($remainder) $page_count++; //나머지가 있으면 총페이지수 1증가

                 $page_range1=$current_page - ($current_page % $showPages);

                 if (($current_page % $showPages) == 0)
                 {
                  $page_range1 -= ($showPages-1);
                 }
                 else
                 {
                  $page_range1++;
                 }

                 $page_range2=$page_range1 + $showPages - 1;

                 // 처음으로
                 if ( $page_range1 > $showPages) {
                    $reresult .= "<a href='{$actionPage}?{$queryName}=0{$a_link}' ";
                    if ( !empty($this->style_a_id) ) $reresult .= "class='{$this->style_a_id}'";
                    $reresult .= ">{$tags_begin}</a>";

                    $reresult .= "{$tags_split2}";
                 }
                 // 이전
                 if ($page_range1 > $showPages)
                 {
                                         //$prev_page=$page_range1 - $showPages;
                                         $prev_page=$page_range1 - 1;
                                         $reresult .= "<a href='{$actionPage}?{$queryName}=".($prev_page-1)."{$a_link}' ";
                                         if ( !empty($this->style_a_id) ) $reresult .= "class='{$this->style_a_id}'";
                                         $reresult .= ">{$tags_prev}</a>";
                 }

                 for ($i=$page_range1; $i <= $page_count && $i<=$page_range2; $i++) {
                          if ($current_page == $i || (!$current_page && $i==1)) {
                                                      $reresult .= $tags_split.str_replace("{index}",$i,$tags_current_index);
                          }
                          else
                          {
                                                  $reresult .= "{$tags_split}<a href='{$actionPage}?{$queryName}=".($i-1)."{$a_link}' ";
                                                           if ( !empty($this->style_a_id) ) $reresult .= "class='{$this->style_a_id}'";
                                                           $reresult .= ">".str_replace("{index}",$i,$tags_index)."</a>";
                          }
                 }

                 if ( $all_data != 0 ) $reresult .= "{$tags_split}";

                 // 다음
                 if ($i <= $page_count)
                 {
                                         $next_page=$i;
                                         $reresult .= "<a href='{$actionPage}?{$queryName}=".($next_page-1)."{$a_link}' ";
                                         if ( !empty($this->style_a_id)) $reresult .= "class='{$this->style_a_id}'";
                                         $reresult .= ">{$tags_next}</a>";
                 }

                 if ( $page_range2 < $page_count ) {
                         $reresult .= "{$tags_split2}";

                        $reresult .= "<a href='{$actionPage}?{$queryName}=".($page_count-1)."{$a_link}' ";
                        if ( !empty($this->style_a_id)) $reresult .= "class='{$this->style_a_id}'";
                        $reresult .= ">{$tags_end}</a>";
                 }
				 return $reresult ;
        }

		
}

?>
