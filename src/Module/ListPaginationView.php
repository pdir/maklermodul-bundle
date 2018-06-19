<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2013 Leo Feyer
 *
 * @package   MaklerModulMplus
 * @author    Mathias Arzberger <develop@pdir.de>
 * @license   All-rights-reserved.
 * @copyright pdir / digital agentur
 */

/**
 * Namespace
 */
namespace Pdir\MaklermodulBundle\Module;

class ListPaginationView extends ListView
{
	/**
	 * @var string
	 */
	protected $strTemplate = 'makler_list_pagination';
	/**
	 * @param \FrontendTemplate $objListView
	 */
	public function __construct($objListView)
	{
		// check if the parameter seems to be ok
		if(!is_object($objListView) || strpos(get_class($objListView), 'FrontendTemplate') === false)
		{
			throw new \Exception('illegal call!');
		}

		$this->page = $this->Input->get('page');
		$this->itemsToShow = !$objListView->makler_paginationCount ? 10 : $objListView->makler_paginationCount;
		$this->linkItemsToShow = !$objListView->makler_paginationLinkCount ? 6 : $objListView->makler_paginationLinkCount;
		$this->showtitle = $objListView->makler_paginationShowtitle;
		$this->detailPagePrefix = $objListView->helper->getDetailViewPrefix();
		$this->data = $objListView->objectData;
	}
	/**
	 * generate
	 * @return string
	 */
	public function generate()
	{
		return parent::generate();
	}
	/**
	 * Generate module
	 */
	protected function compile()
	{
		// get page
		global $objPage;
		$arrAllArticles = array();
		$arrArticles = array();
		$intCounter = 0;
		$intActive = 0;
		$intTime = time();
		
		if(!$this->page) $this->page = 0;
		
		$pages = array_chunk($this->data, $this->itemsToShow);
		
		// first page
		$arrArticle = array
		(
			'isActive' => $this->page == 0 ? true : false,
			'href' => ampersand($this->generateFrontendUrl($objPage->row())),
			'title' => specialchars(1),
           	'link' => !$this->showtitle ? 1 : $objPage->title,
		);
		$arrAllArticles[0] = $arrArticle;
		
		for($i=1; $i< count($pages); $i++){
			
			$arrArticle = array
			(
					'isActive' => $this->page == $i ? true : false,
					'href' => ampersand($this->generateFrontendUrl($objPage->row(), '/page/'.$i)),
					'title' => specialchars($i+1),
                	'link' => !$this->showtitle ? $i+1 : $objPage->title,
			);
			$intCounter++;
			$arrAllArticles[$i] = $arrArticle;
		}
		
		// assign total
		$this->Template->total = sprintf($GLOBALS['TL_LANG']['MSC']['totalPages'], $this->page+1, $intCounter+1);
		// assign array first
		if($this->page > 2)
		{
			$arrFirst = reset($arrAllArticles);
			$arrFirst['link'] = $GLOBALS['TL_LANG']['MSC']['first'];
			$this->Template->first = $arrFirst;
		}
		// assign array prev
		if($this->page >= 1)
		{
			$arrPrevious = $arrAllArticles[($this->page)-1];
			$arrPrevious['link'] = $GLOBALS['TL_LANG']['MSC']['previous'];
			$this->Template->previous = $arrPrevious;
		}
		// assign array next
		if($this->page < $intCounter)
		{
			$arrNext = $arrAllArticles[($this->page)+1];
			$arrNext['link'] = $GLOBALS['TL_LANG']['MSC']['next'];
			$this->Template->next = $arrNext;
		}
		// assign array last
		if($this->page < $intCounter-1)
		{
			$arrLast = end($arrAllArticles);
			$arrLast['link'] = $GLOBALS['TL_LANG']['MSC']['last'];
			$this->Template->last = $arrLast;
		}
		
		// check if we show all
		if($this->linkItemsToShow > 0) {
			// set start at
			$intStartAt = floor($this->page - ($this->linkItemsToShow / 2));
			$intStartAt = $intCounter - $this->linkItemsToShow > $intStartAt ? $intStartAt : $intCounter - $this->linkItemsToShow;
			$intStartAt = $intStartAt > 1 ?$intStartAt : 1;
			// set stop at
			$intStopAt = $intStartAt + $this->linkItemsToShow;
			$intStopAt = $intStopAt <= $intCounter ? $intStopAt : $intCounter;
			// assign start at if bigger than one
			if($intStartAt > 1)
			{
				$arrStartAt = $arrAllArticles[$intStartAt-1];
				$arrStartAt['link'] = $GLOBALS['TL_LANG']['MSC']['points'];
				$this->Template->startat = $arrStartAt;
			}
			
			// assign stop at if its smaller the count
			if($intStopAt < $intCounter)
			{
				$arrStopAt = $arrAllArticles[$intStopAt+1];
				$arrStopAt['link'] = $GLOBALS['TL_LANG']['MSC']['points'];
				$this->Template->stopat = $arrStopAt;
			}
			
			// fill article to show array
			foreach($arrAllArticles as $intKey => $arrArticle)
			{
				if($intKey >= $intStartAt && $intKey <= $intStopAt)
				{
					$arrArticles[$intKey] = $arrArticle;
				}
			}
			
			// assign all articles
			$this->Template->articles = $arrArticles;
		} else {
			// assign articles
			$this->Template->articles = $arrAllArticles;
		}

	}
}
