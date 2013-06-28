[1mdiff --git a/Classes/Controller/AdministrationController.php b/Classes/Controller/AdministrationController.php[m
[1mindex 61f4fc0..a39f2ee 100644[m
[1m--- a/Classes/Controller/AdministrationController.php[m
[1m+++ b/Classes/Controller/AdministrationController.php[m
[36m@@ -87,6 +87,7 @@[m [mclass Tx_News_Controller_AdministrationController extends Tx_News_Controller_New[m
 	 *[m
 	 * @param Tx_News_Domain_Model_Dto_AdministrationDemand $demand[m
 	 * @dontvalidate  $demand[m
[32m+[m	[32m * @ignorevalidation[m
 	 * @return void[m
 	 */[m
 	public function indexAction(Tx_News_Domain_Model_Dto_AdministrationDemand $demand = NULL) {[m
[1mdiff --git a/Classes/Domain/Model/Dto/AdministrationDemand.php b/Classes/Domain/Model/Dto/AdministrationDemand.php[m
[1mindex 5aec5f4..ddd538b 100644[m
[1m--- a/Classes/Domain/Model/Dto/AdministrationDemand.php[m
[1m+++ b/Classes/Domain/Model/Dto/AdministrationDemand.php[m
[36m@@ -36,7 +36,7 @@[m [mclass Tx_News_Domain_Model_Dto_AdministrationDemand extends Tx_News_Domain_Model[m
 	protected $recursive;[m
 [m
 	/**[m
[31m-	 * @var array[m
[32m+[m	[32m * @var array<string>[m
 	 */[m
 	protected $selectedCategories = array();[m
 [m
[36m@@ -66,11 +66,11 @@[m [mclass Tx_News_Domain_Model_Dto_AdministrationDemand extends Tx_News_Domain_Model[m
 	 * @return array[m
 	 */[m
 	public function getSelectedCategories() {[m
[31m-		return $this->selectedCategories;[m
[32m+[m		[32mreturn (array)$this->selectedCategories;[m
 	}[m
 [m
 	/**[m
[31m-	 * @param $selectedCategories[m
[32m+[m	[32m * @param array $selectedCategories[m
 	 * @return void[m
 	 */[m
 	public function setSelectedCategories($selectedCategories) {[m
[1mdiff --git a/Resources/Private/Language/locallang_be.xml b/Resources/Private/Language/locallang_be.xml[m
[1mindex f339084..140095e 100644[m
[1m--- a/Resources/Private/Language/locallang_be.xml[m
[1m+++ b/Resources/Private/Language/locallang_be.xml[m
[36m@@ -17,7 +17,6 @@[m
 			<label index="flexforms_general.mode">What to display</label>[m
 			<label index="flexforms_general.mode.news_list">List view</label>[m
 			<label index="flexforms_general.mode.news_listOnly">List view (without overloading detail view)</label>[m
[31m-			<label index="flexforms_general.mode.news_latest">Latest view</label>[m
 			<label index="flexforms_general.mode.news_detail">Detail view</label>[m
 			<label index="flexforms_general.mode.news_searchform">Search form</label>[m
 			<label index="flexforms_general.mode.news_searchresult">Search result</label>[m
[36m@@ -133,7 +132,7 @@[m
 			<label index="tag_suggest">Tag "%s" could not be found, wanna create it? &lt;strong&gt;Click here&lt;/strong&gt;</label>[m
 			<label index="tag_suggest_error_no-tag">No tag submitted!</label>[m
 			<label index="tag_suggest_error_no-newsid">No news id given!</label>[m
[31m-			<label index="tag_suggest_error_no-pid-found">No pid for the tag record could be find by reading settings of Extension Manager!</label>[m
[32m+[m			[32m<label index="tag_suggest_error_no-pid-found">No pid for the tag record could be found by reading settings of Extension Manager!</label>[m
 			<label index="tag_suggest_error_no-tag-created">No new tag created!</label>[m
 			<label index="tag_suggest_error_no-pid-defined">No page defined to save tag. Please add the id in the settings of the extension manager.</label>[m
 [m
[36m@@ -169,7 +168,6 @@[m
 [m
 			<label index="flexforms_general.mode">Visnings-valg</label>[m
 			<label index="flexforms_general.mode.news_list">Liste-visning</label>[m
[31m-			<label index="flexforms_general.mode.news_latest">Nyeste-visning</label>[m
 			<label index="flexforms_general.mode.news_detail">Detalje-visning</label>[m
 			<label index="flexforms_general.mode.news_searchform">Søgeformular</label>[m
 			<label index="flexforms_general.mode.news_searchresult">Søgeresultater</label>[m
[36m@@ -286,7 +284,6 @@[m
 [m
 			<label index="flexforms_general.mode">Qué mostrar</label>[m
 			<label index="flexforms_general.mode.news_list">Vista de lista</label>[m
[31m-			<label index="flexforms_general.mode.news_latest">Vista de últimas noticias</label>[m
 			<label index="flexforms_general.mode.news_detail">Vista de detalle</label>[m
 			<label index="flexforms_general.mode.news_searchform">Formulario de búsqueda</label>[m
 			<label index="flexforms_general.mode.news_searchresult">Resultados de búsqueda</label>[m
[36m@@ -353,14 +350,152 @@[m
 		</languageKey>[m
 [m
 		<languageKey index="de" type="array">[m
[32m+[m			[32m<label index="pi1_title">News system</label>[m
[32m+[m			[32m<label index="pi1_plus_wiz_description">Vielfältiges Artikel Plugin</label>[m
[32m+[m
[32m+[m			[32m<!-- Flexforms -->[m
[32m+[m			[32m<label index="flexforms_tab.settings">Einstellungen</label>[m
[32m+[m			[32m<label index="flexforms_tab.additional">Zusätzlich</label>[m
[32m+[m			[32m<label index="flexforms_tab.template">Template</label>[m
 [m
[32m+[m			[32m<label index="flexforms_general.mode">Modus // Ansicht</label>[m
[32m+[m			[32m<label index="flexforms_general.mode.news_list">Liste</label>[m
[32m+[m			[32m<label index="flexforms_general.mode.news_listOnly">Liste (ohne Möglichkeit Detailansicht anzuzeigen)</label>[m
[32m+[m			[32m<label index="flexforms_general.mode.news_detail">Detail</label>[m
[32m+[m			[32m<label index="flexforms_general.mode.news_searchform">Suchformular</label>[m
[32m+[m			[32m<label index="flexforms_general.mode.news_searchresult">Suchergebnis</label>[m
[32m+[m			[32m<label index="flexforms_general.mode.news_datemenu">Datumsmenü</label>[m
[32m+[m			[32m<label index="flexforms_general.mode.category_list">Kategoriemenü</label>[m
[32m+[m			[32m<label index="flexforms_general.mode.not_configured">Es wurde kein Modus ausgewählt</label>[m
[32m+[m			[32m<label index="flexforms_general.mode.optgroup_search">Suche</label>[m
[32m+[m			[32m<label index="flexforms_general.mode.optgroup_others">Andere</label>[m
[32m+[m			[32m<label index="flexforms_general.mode.tag_list">Liste von Tags</label>[m
[32m+[m			[32m<label index="flexforms_general.archiveRestriction">Archiv</label>[m
[32m+[m			[32m<label index="flexforms_general.archiveRestriction.active">Nur aktive (nicht archivierte)</label>[m
[32m+[m			[32m<label index="flexforms_general.archiveRestriction.archived">Archivierte</label>[m
[32m+[m			[32m<label index="flexforms_general.orderBy">Sortierung</label>[m
[32m+[m			[32m<label index="flexforms_general.orderBy.uid">Uid</label>[m
[32m+[m			[32m<label index="flexforms_general.orderBy.sorting">Manuelle Sortierung</label>[m
[32m+[m			[32m<label index="flexforms_general.orderBy.tstamp">Letzte Bearbeitung</label>[m
[32m+[m			[32m<label index="flexforms_general.orderBy.crdate">Erstellungsdatum</label>[m
[32m+[m			[32m<label index="flexforms_general.orderBy.datetime">Angegebene Zeit</label>[m
[32m+[m			[32m<label index="flexforms_general.orderBy.title">Title</label>[m
[32m+[m			[32m<label index="flexforms_general.orderDirection">Sortierrichtung</label>[m
[32m+[m			[32m<label index="flexforms_general.orderDirection.asc">Aufsteigend</label>[m
[32m+[m			[32m<label index="flexforms_general.orderDirection.desc">Absteigend</label>[m
[32m+[m
[32m+[m			[32m<label index="flexforms_general.dateField">Verwendetes Datumsfeld</label>[m
[32m+[m			[32m<label index="flexforms_general.dateField.datetime">Datum</label>[m
[32m+[m			[32m<label index="flexforms_general.dateField.archive">Archivdatum</label>[m
[32m+[m
[32m+[m			[32m<label index="flexforms_general.categories">Kategorien</label>[m
[32m+[m			[32m<label index="flexforms_general.categoryConjunction">Kategoriemodus</label>[m
[32m+[m			[32m<label index="flexforms_general.categoryConjunction.all">Auswahl ignorieren</label>[m
[32m+[m			[32m<label index="flexforms_general.categoryConjunction.or">Anzeige der Artikel mit ausgewählten Kategorien (OR)</label>[m
[32m+[m			[32m<label index="flexforms_general.categoryConjunction.and">Anzeige der Artikel mit ausgewählten Kategorien (AND)</label>[m
[32m+[m			[32m<label index="flexforms_general.categoryConjunction.notand">Do NOT show items with selected categories (AND)</label>[m
[32m+[m			[32m<label index="flexforms_general.categoryConjunction.notor">Do NOT show items with selected categories (OR)</label>[m
[32m+[m			[32m<label index="flexforms_general.includeSubCategories">Subkategorien inkludieren</label>[m
[32m+[m			[32m<label index="flexforms_general.timeRestriction">Untere Datumsbeschränkung: Älter als angegebene Zeit</label>[m
[32m+[m			[32m<label index="flexforms_general.timeRestrictionHigh">Obere Datumsbeschränkung: Jünger als angegebene Zeit</label>[m
[32m+[m			[32m<label index="flexforms_general.topNewsRestriction">Top Artikel</label>[m
[32m+[m			[32m<label index="flexforms_general.topNewsRestriction.1">ausschließlich Top Artikel</label>[m
[32m+[m			[32m<label index="flexforms_general.topNewsRestriction.2">keine Top Artikel</label>[m
[32m+[m			[32m<label index="flexforms_general.singleNews">Anzeige eines einzelnen Artikels</label>[m
[32m+[m			[32m<label index="flexforms_general.previewHiddenRecords">Vorschau von versteckten Artikeln erlauben</label>[m
[32m+[m			[32m<label index="flexforms_general.previewHiddenRecords.I.0">Nein</label>[m
[32m+[m			[32m<label index="flexforms_general.previewHiddenRecords.I.1">Ja</label>[m
[32m+[m			[32m<label index="flexforms_general.previewHiddenRecords.I.2">Im TypoScript definiert</label>[m
 			<label index="flexforms_general.no-constraint">[keine Einschränkung]</label>[m
 [m
[32m+[m			[32m<label index="flexforms_template.mediaMaxWidth">Maximale Breite für Medienelemente</label>[m
[32m+[m			[32m<label index="flexforms_template.mediaMaxHeight">Maximale Höhe für Medienelemente</label>[m
[32m+[m			[32m<label index="flexforms_template.cropMaxCharacters">Länge des Teasers (Anzahl Buchstaben)</label>[m
[32m+[m			[32m<label index="flexforms_template.templateLayout">Template Layout</label>[m
[32m+[m
[32m+[m			[32m<label index="flexforms_additional.templateSwitch.1">1</label>[m
[32m+[m			[32m<label index="flexforms_additional.templateSwitch.2">2</label>[m
[32m+[m			[32m<label index="flexforms_additional.detailPid">Seite für Detailansicht</label>[m
[32m+[m			[32m<label index="flexforms_additional.backPid">Seite für den Link zurück</label>[m
[32m+[m			[32m<label index="flexforms_additional.listPid">Seite für Listenansicht</label>[m
[32m+[m			[32m<label index="flexforms_additional.limit">Maximale Anzahl an Artikeln</label>[m
[32m+[m			[32m<label index="flexforms_additional.offset">Starte mit Artikel Nummer </label>[m
[32m+[m			[32m<label index="flexforms_additional.hidePagination">Pagination verbergen</label>[m
[32m+[m			[32m<label index="flexforms_additional.topNewsFirst">Top Artikel vorreihen</label>[m
[32m+[m			[32m<label index="flexforms_additional.disableOverrideDemand">"Override demand" deaktivieren</label>[m
[32m+[m			[32m<label index="flexforms_additional.excludeAlreadyDisplayedNews">Bereits angezeigte Artikel verbergen</label>[m
[32m+[m
[32m+[m			[32m<label index="pagemodule.pageNotAvailable">Die Seite mit der ID "%s" ist nicht mehr verfügbar!</label>[m
[32m+[m			[32m<label index="pagemodule.newsNotAvailable">Der Artikel mit der ID "%s" ist nicht mehr verfügbar!</label>[m
[32m+[m			[32m<!-- Page Module Record list -->[m
[32m+[m			[32m<label index="pagemodule_simple">Einfache Ansicht</label>[m
[32m+[m			[32m<label index="pagemodule_advanced">Erweiterte Ansicht</label>[m
[32m+[m			[32m<label index="pagemodule_complex">Volle Ansicht</label>[m
[32m+[m
[32m+[m			[32m<!-- Extension Manager Configuration -->[m
[32m+[m			[32m<label index="extmng.pageModuleFieldsNews">News fields shown in page module:Set the fields which should be shown in the page module by using the following syntax:[m
[32m+[m				[32mlabel=fieldlist;label=fieldlist[m
[32m+[m			[32m</label>[m
[32m+[m			[32m<label index="extmng.pageModuleFieldsCategory">Category fields shown in page module:Set the fields which should be shown in the page module.</label>[m
[32m+[m			[32m<label index="extmng.prependAtCopy">Prepend at copy: Prepend string "copy X".</label>[m
[32m+[m			[32m<label index="extmng.categoryRestriction">Category restriction: Restrict the available categories in news records. PageTsConfig:[m
[32m+[m				[32mTCEFORM.tx_news_domain_model_news.categories.PAGE_TSCONFIG_ID=120. This feature is currently under development and not beeing expected to work![m
[32m+[m			[32m</label>[m
[32m+[m			[32m<label index="extmng.categoryRestriction.current_pid">Categories from current page</label>[m
[32m+[m			[32m<label index="extmng.categoryRestriction.storage_pid">Categories from page which is defined in page properties</label>[m
[32m+[m			[32m<label index="extmng.categoryRestriction.siteroot">Categories from site root</label>[m
[32m+[m			[32m<label index="extmng.categoryRestriction.pages_tsconfig">Page TsConfig</label>[m
[32m+[m			[32m<label index="extmng.categoryRestriction.none">No restriction</label>[m
[32m+[m			[32m<label index="extmng.manualSorting">Enable manual sorting of news records</label>[m
[32m+[m			[32m<label index="extmng.contentElementRelation">Use content element relation:If set, content elements can be added to news records.</label>[m
[32m+[m			[32m<label index="extmng.tagPid">Define pid of tag records:Tags are collected on one central page.</label>[m
[32m+[m			[32m<label index="extmng.archiveDate">Archive Date: Define if the archive date is a "Date" or "Date &amp; Time"</label>[m
[32m+[m			[32m<label index="extmng.archiveDate.date">Date</label>[m
[32m+[m			[32m<label index="extmng.archiveDate.datetime">Date &amp; Time</label>[m
[32m+[m			[32m<label index="extmng.removeListActionFromFlexforms">List actions shown in Flexforms:There are 2 different list views: "List only" can only show the list view. This is[m
[32m+[m				[32mfor example neded if you need a list view at the same page as the detail view. "List+Detail" will show the same list view but will show the detail view if a news[m
[32m+[m				[32mitem is set via GET arguments. Be aware: If the setting is changed after news plugins have been created, all plugins need to be reconfigured by selecting the[m
[32m+[m				[32mcorrect action![m
[32m+[m			[32m</label>[m
[32m+[m			[32m<label index="extmng.removeListActionFromFlexforms.0">Both views are shown</label>[m
[32m+[m			[32m<label index="extmng.removeListActionFromFlexforms.1">List &amp; Detail</label>[m
[32m+[m			[32m<label index="extmng.removeListActionFromFlexforms.2">List only</label>[m
[32m+[m			[32m<label index="extmng.showImporter">Show importer:Backend module to import into news</label>[m
[32m+[m			[32m<label index="extmng.showAdministrationModule">Show administration module:Backend module to administrate news records</label>[m
[32m+[m			[32m<label index="extmng.showMediaDescriptionField">Show description for media elements:If set you get an additional description field for media elements.</label>[m
[32m+[m
[32m+[m			[32m<!-- BE User Settings -->[m
[32m+[m			[32m<label index="usersettings.overlay">Use this overlay for news categories in the Backend</label>[m
[32m+[m			[32m<label index="usersettings.no-languages-available">There are no languages available, so nothing to choose.</label>[m
[32m+[m
 			<!-- BE Module -->[m
 			<label index="ttnews_importer_title">Import von tt_news Datensätzen</label>[m
 			<label index="ttnewscategory_importer_title">Import von tt_news Kategorien</label>[m
[31m-			<label index="start_import">Start import with %s record(s).</label>[m
[31m-			<label index="tag_suggest">Der Tag "%s" wurde nicht gefunden. Wollen Sie diesen erstellen? &lt;strong&gt;Klicken Sie hier&lt;/strong&gt;</label>[m
[32m+[m			[32m<label index="start_import">Import mit %s Datensätzen starten.</label>[m
[32m+[m
[32m+[m			[32m<label index="tag_suggest">Der Tag "%s" wurde nicht gefunden. Zum erstellen &lt;