<?
class pager {
        // �⺻ �Է°�
        var $allRows; //�ѷ��ڵ��
        var $showRows; // ���������� ������ ���ڵ��
        var $curPage; // ����������(0�����ͽ���)

        // �ɼ�
        var $showPages=10; //����������
        var $queryName="page"; //������ �Ѿ ������ ���� �̸�(���������� �ΰ� �̻��� ����¡�� �������� ���ؼ�...)
        var $actionPage; //�׼�������

        var $subQuery = array(); //���������� addQuery�Լ� ����ؼ� �߰�

        var $tags_prev = "<"; //���� ��������
        var $tags_next = ">"; //���� ��������
        var $tags_begin = "<<"; //ó�� ������
        var $tags_end = ">>"; //������ ������
        var $tags_split2 = "&nbsp;"; // begin�� prev , next�� end �и���
        var $tags_split = "&nbsp;"; //������ �и���
        var $tags_current_index="{index}"; //���� ������, {index} �� ������������ ��Ÿ��
        var $tags_index="{index}"; //������,  {index} �� ������������ ��Ÿ��

        var $style_a_id = ""; //��ũ ��Ÿ�� ���̵�

        function pager($curPage, $allRows, $showRows=10)//���������� , ��ü����, ���������� �����ٰ���
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
                 // �⺻ �ʿ��� ����
                 foreach( $this->subQuery as $key=>$value) {
                         $a_link .= "&".$key."=".$value;
                 }
                 $current_page = $this->curPage+1; //�������ѹ�
                 $list_per_page = $this->showRows; //�������� ������ ����Ÿ
                 $all_data = $this->allRows; //�ѵ���Ÿ
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

                 $remainder=$all_data % $list_per_page; // ����¡ī��Ʈ�� ������
                 $page_count=($all_data - $remainder) / $list_per_page; //����������

                 if ($remainder) $page_count++; //�������� ������ ���������� 1����

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

                 // ó������
                 if ( $page_range1 > $showPages) {
                    echo "<a href='{$actionPage}?{$queryName}=0{$a_link}' ";
                    if ( !empty($this->style_a_id) ) echo "class='{$this->style_a_id}'";
                    echo ">{$tags_begin}</a>";

                    echo "{$tags_split2}";
                 }
                 // ����
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

                 // ����
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
                 // �⺻ �ʿ��� ����
				 $reresult = "";
                 foreach( $this->subQuery as $key=>$value) {
                         $a_link .= "&".$key."=".$value;
                 }
                 $current_page = $this->curPage+1; //�������ѹ�
                 $list_per_page = $this->showRows; //�������� ������ ����Ÿ
                 $all_data = $this->allRows; //�ѵ���Ÿ
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

                 $remainder=$all_data % $list_per_page; // ����¡ī��Ʈ�� ������
                 $page_count=($all_data - $remainder) / $list_per_page; //����������

                 if ($remainder) $page_count++; //�������� ������ ���������� 1����

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

                 // ó������
                 if ( $page_range1 > $showPages) {
                    $reresult .= "<a href='{$actionPage}?{$queryName}=0{$a_link}' ";
                    if ( !empty($this->style_a_id) ) $reresult .= "class='{$this->style_a_id}'";
                    $reresult .= ">{$tags_begin}</a>";

                    $reresult .= "{$tags_split2}";
                 }
                 // ����
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

                 // ����
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
