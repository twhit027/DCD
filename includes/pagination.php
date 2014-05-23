<?php
/**
 * Created by DCDGroup.
 * User: JHICKS
 * Date: 5/21/14
 * Time: 1:42 PM
 */

$pagination = "";
if ($listings['totalRows'] > LISTINGS_PER_PAGE) {
    $total_pages = $listings['totalRows'];
    // How many adjacent pages should be shown on each side?
    $adjacents = 3;

    /* Setup vars for query. */
    $targetPage = 'category.php?place=' . $placement . '&posit=' . $position . '&ft=' . $fullText ; //your file name  (the name of this file)
    $limit = LISTINGS_PER_PAGE; //how many items to show per page
    //$page = urldecode($_REQUEST['page']);

    if ($page)
        $start = ($page - 1) * $limit; //first item to display on this page
    else
        $start = 0; //if no page var is given, set start to 0

    /* Setup page vars for display. */
    if ($page == 0) $page = 1; //if no page var is given, default to 1.
    $prev = $page - 1; //previous page is page - 1
    $next = $page + 1; //next page is page + 1
    $lastpage = ceil($total_pages / $limit); //lastpage is = total pages / items per page, rounded up.
    $lpm1 = $lastpage - 1; //last page minus 1

    if ($lastpage > 1) {
        //previous button
        if ($page > 1)
            $pagination .= '<ul class="pagination"><li><a href="category.php?place=' . $placement . '&posit=' . $position . '&page=' . ($page - 1) . '&ft=' . $fullText . '">&laquo;</a></li>';
        else
            $pagination .= '<ul class="pagination"><li class="disabled"><a href="#">&laquo;</a></li>';

        //pages
        if ($lastpage < 4 + ($adjacents * 2)) { //not enough pages to bother breaking it up
            for ($counter = 1; $counter <= $lastpage; $counter++) {
                if ($counter == $page)
                    $pagination .= '<li class="active"><a href="'.$targetPage.'&page=' . $counter . '">' . $counter . ' <span class="sr-only">(currecnt)</span></a></li>';
                else
                    $pagination .= '<li><a href="' .$targetPage. '&page=' . $counter . '">' . $counter . '</a></li>';
            }
        } elseif ($lastpage > 5 + ($adjacents * 2)) { //enough pages to hide some
            //close to beginning; only hide later pages
            if ($page < 1 + ($adjacents * 2)) {
                for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++) {
                    if ($counter == $page)
                        $pagination .= "<span class=\"current\">$counter</span>";
                    else
                        $pagination .= "<a href=\"$targetPage&page=$counter\">$counter</a>";
                }
                $pagination .= "...";
                $pagination .= "<a href=\"$targetPage?page=$lpm1\">$lpm1</a>";
                $pagination .= "<a href=\"$targetPage?page=$lastpage\">$lastpage</a>";
            } elseif ($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2)) { //in middle; hide some front and some back
                $pagination .= "<a href=\"$targetPage?page=1\">1</a>";
                $pagination .= "<a href=\"$targetPage?page=2\">2</a>";
                $pagination .= "...";
                for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++) {
                    if ($counter == $page)
                        $pagination .= "<span class=\"current\">$counter</span>";
                    else
                        $pagination .= "<a href=\"$targetPage?page=$counter\">$counter</a>";
                }
                $pagination .= "...";
                $pagination .= "<a href=\"$targetPage?page=$lpm1\">$lpm1</a>";
                $pagination .= "<a href=\"$targetPage?page=$lastpage\">$lastpage</a>";
            } else { //close to end; only hide early pages
                $pagination .= "<a href=\"$targetPage?page=1\">1</a>";
                $pagination .= "<a href=\"$targetPage?page=2\">2</a>";
                $pagination .= "...";
                for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++) {
                    if ($counter == $page)
                        $pagination .= "<span class=\"current\">$counter</span>";
                    else
                        $pagination .= "<a href=\"$targetPage?page=$counter\">$counter</a>";
                }
            }
        }

        //next button
        if ($page < $counter - 1)
            $pagination .= '<li><a href="'.$targetPage . '&page=' . ($page + 1) . '">&raquo;</a></li></ul>';
        else
            $pagination .= '<li class="disabled"><a href="#">&raquo;</a></li></ul>';
    }
}