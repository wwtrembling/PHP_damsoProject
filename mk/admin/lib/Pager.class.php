<?
class Pager {
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

        var $a_prev; //이전 몇페이지
        var $a_next; //다음 몇페이지
        var $a_begin; //처음 페이지
        var $a_end; //마지막 페이지
        var $a_idx; //페이지
        var $a_curno; //현재페이지번호

        public function __construct($curPage, $allRows, $showRows=10)//현재페이지 , 전체개수, 한페이지에 보여줄개수
        {
			global $PHP_SELF;
            $this->curPage = $curPage;
            $this->allRows = $allRows;
            $this->showRows = $showRows;
            $this->actionPage = $PHP_SELF;
        }

        public function addQuery($key, $value)
        {
            $this->subQuery[$key] = $value;
        }

        public function stroke($p=true)
        {
            $a_link = '';
             // 기본 필요한 내용
             foreach( $this->subQuery as $key=>$value) {
                     $a_link .= "&amp;".$key."=".$value;
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
			 $a_begin = &$this->a_begin;
			 $a_end = &$this->a_end;
			 $a_prev = &$this->a_prev;
			 $a_next = &$this->a_next;
			 $a_idx = &$this->a_idx;
			 $a_idx_num = 0;
			 $a_curno = &$this->a_curno;

             $remainder=$all_data % $list_per_page; // 페이징카운트후 나머지
             //$page_count=($all_data - $remainder) / $list_per_page; //총페이지수
             //if ($remainder) $page_count++; //나머지가 있으면 총페이지수 1증가
			 if ( $list_per_page ) $page_count = ceil($all_data/$list_per_page);
			 else $list_per_page = 0;

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
				 if ( $p ) {
					echo "<a href='{$actionPage}?{$queryName}=0{$a_link}' ";
					if ( !empty($this->style_a_id) ) echo "class='{$this->style_a_id}'";
					echo ">{$tags_begin}</a>";

					echo "{$tags_split2}";
				 }

				 $a_begin = "{$actionPage}?{$queryName}=0{$a_link}";

             }
             // 이전
             if ($page_range1 > $showPages)
             {
				 $prev_page=$page_range1 - 1;
				 if ( $p ) {
					 //$prev_page=$page_range1 - $showPages;
					 echo "<a href='{$actionPage}?{$queryName}=".($prev_page-1)."{$a_link}' ";
					 if ( !empty($this->style_a_id) ) echo "class='{$this->style_a_id}'";
					 echo ">{$tags_prev}</a>";
				 }

				 $a_prev = "{$actionPage}?{$queryName}=".($prev_page-1)."{$a_link}";
             }

             for ($i=$page_range1; $i <= $page_count && $i<=$page_range2; $i++) {
				if ($current_page == $i || (!$current_page && $i==1)) {
					if ( $p ) echo $tags_split.str_replace("{index}",$i,$tags_current_index);
					//$a_idx[$a_idx_num]['current'] = true;
					$a_curno = $i;
				}
				else
				{
					if ( $p ) {
						echo "{$tags_split}<a href='{$actionPage}?{$queryName}=".($i-1)."{$a_link}' ";
						if ( !empty($this->style_a_id) ) echo "class='{$this->style_a_id}'";
						echo ">".str_replace("{index}",$i,$tags_index)."</a>";
					}
				}
				
				//if ( !isset($a_idx[$a_idx_num]['current']) ) $a_idx[$a_idx_num]['current'] = false;
				$a_idx[$a_idx_num]['no'] = $i;
				$a_idx[$a_idx_num]['link'] = "{$actionPage}?{$queryName}=".($i-1)."{$a_link}";
				$a_idx_num++;

             }

             if ( $all_data != 0 ) if ( $p ) echo "{$tags_split}";

             // 다음
             if ($i <= $page_count)
             {
				 $next_page=$i;
				 if ( $p ) {
					 echo "<a href='{$actionPage}?{$queryName}=".($next_page-1)."{$a_link}' ";
					 if ( !empty($this->style_a_id)) echo "class='{$this->style_a_id}'";
					 echo ">{$tags_next}</a>";
				 }

				 $a_next = "{$actionPage}?{$queryName}=".($next_page-1)."{$a_link}";
             }

             if ( $page_range2 < $page_count ) {
				 if ( $p ) {
					echo "{$tags_split2}";

					echo "<a href='{$actionPage}?{$queryName}=".($page_count-1)."{$a_link}' ";
					if ( !empty($this->style_a_id)) echo "class='{$this->style_a_id}'";
					echo ">{$tags_end}</a>";
				 }

				 $a_end = "{$actionPage}?{$queryName}=".($page_count-1)."{$a_link}";
             }
        }

}

?>