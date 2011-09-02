page = PAGE
page.10 < styles.content.get

config {
	# deactivate Standard-Header
	disableAllHeaderCode = 1
	# no xhtml tags
	xhtml_cleaning = none
	admPanel = 0
	metaCharset = utf-8
	# define charset
	additionalHeaders = Content-Type:text/xml;charset=utf-8
	disablePrefixComment = 1
}

# set the format
plugin.tx_news.settings.format = xml