<?
class Pager {
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

        var $a_prev; //���� ��������
        var $a_next; //���� ��������
        var $a_begin; //ó�� ������
        var $a_end; //������ ������
        var $a_idx; //������
        var $a_curno; //������������ȣ

        public function __construct($curPage, $allRows, $showRows=10)//���������� , ��ü����, ���������� �����ٰ���
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
             // �⺻ �ʿ��� ����
             foreach( $this->subQuery as $key=>$value) {
                     $a_link .= "&amp;".$key."=".$value;
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
			 $a_begin = &$this->a_begin;
			 $a_end = &$this->a_end;
			 $a_prev = &$this->a_prev;
			 $a_next = &$this->a_next;
			 $a_idx = &$this->a_idx;
			 $a_idx_num = 0;
			 $a_curno = &$this->a_curno;

             $remainder=$all_data % $list_per_page; // ����¡ī��Ʈ�� ������
             //$page_count=($all_data - $remainder) / $list_per_page; //����������
             //if ($remainder) $page_count++; //�������� ������ ���������� 1����
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

             // ó������
             if ( $page_range1 > $showPages) {
				 if ( $p ) {
					echo "<a href='{$actionPage}?{$queryName}=0{$a_link}' ";
					if ( !empty($this->style_a_id) ) echo "class='{$this->style_a_id}'";
					echo ">{$tags_begin}</a>";

					echo "{$tags_split2}";
				 }

				 $a_begin = "{$actionPage}?{$queryName}=0{$a_link}";

             }
             // ����
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

             // ����
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