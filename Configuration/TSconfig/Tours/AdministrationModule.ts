mod.guide.tours.TxNewsAdministrationModule {
    title = LLL:EXT:news/Resources/Private/Language/Tours.xlf:adminModule.title
    description = LLL:EXT:news/Resources/Private/Language/Tours.xlf:adminModule.description
    moduleName = web_NewsAdministration
    iconIdentifier = ext-news-type-default
    steps {
        # General
        10 {
            selector = h1
            title = LLL:EXT:news/Resources/Private/Language/Tours.xlf:adminModule.general.title
            content = LLL:EXT:news/Resources/Private/Language/Tours.xlf:adminModule.general.content
            placement = bottom
            showArrow = true
        }

        # Filter
        20 {
            selector = .icon-actions-filter
            title = LLL:EXT:news/Resources/Private/Language/Tours.xlf:adminModule.filter.title
            content = LLL:EXT:news/Resources/Private/Language/Tours.xlf:adminModule.filter.content
            placement = right
            showArrow = true
        }

        21 {
            selector = .icon-actions-filter
            title = LLL:EXT:news/Resources/Private/Language/Tours.xlf:adminModule.filter2.title
            content = LLL:EXT:news/Resources/Private/Language/Tours.xlf:adminModule.filter2.content
            placement = right
            showArrow = true
        }

        31 {
            selector = #searchWord
            title = LLL:EXT:news/Resources/Private/Language/Tours.xlf:adminModule.searchWord.title
            content = LLL:EXT:news/Resources/Private/Language/Tours.xlf:adminModule.searchWord.content
            placement = right
            showArrow = true
        }

        32 {
            selector = #timeRestriction
            title = LLL:EXT:news/Resources/Private/Language/Tours.xlf:adminModule.timeRestriction.title
            content = LLL:EXT:news/Resources/Private/Language/Tours.xlf:adminModule.timeRestriction.content
            placement = right
            showArrow = true
        }

        33 {
            selector = #topNewsRestriction
            title = LLL:EXT:news/Resources/Private/Language/Tours.xlf:adminModule.topNewsRestriction.title
            content = LLL:EXT:news/Resources/Private/Language/Tours.xlf:adminModule.topNewsRestriction.content
            placement = right
            showArrow = true
        }

        34 {
            selector = #sortingField
            title = LLL:EXT:news/Resources/Private/Language/Tours.xlf:adminModule.sortingField.title
            content = LLL:EXT:news/Resources/Private/Language/Tours.xlf:adminModule.sortingField.content
            placement = right
            showArrow = true
        }

        35 {
            selector = #recursive
            title = LLL:EXT:news/Resources/Private/Language/Tours.xlf:adminModule.recursive.title
            content = LLL:EXT:news/Resources/Private/Language/Tours.xlf:adminModule.recursive.content
            placement = right
            showArrow = true
        }

        36 {
            selector = .category-tree
            title = LLL:EXT:news/Resources/Private/Language/Tours.xlf:adminModule.categoryTree.title
            content = LLL:EXT:news/Resources/Private/Language/Tours.xlf:adminModule.categoryTree.content
            placement = left
            showArrow = true
        }

        37 {
            selector = #categoryConjunction
            title = LLL:EXT:news/Resources/Private/Language/Tours.xlf:adminModule.categoryConjunction.title
            content = LLL:EXT:news/Resources/Private/Language/Tours.xlf:adminModule.categoryConjunction.content
            placement = left
            showArrow = true
        }

        100 {
            selector = .t3-js-jumpMenuBox
            title = LLL:EXT:news/Resources/Private/Language/Tours.xlf:adminModule.actionSwitch.title
            content = LLL:EXT:news/Resources/Private/Language/Tours.xlf:adminModule.actionSwitch.content
            placement = right
            showArrow = true
        }
    }
}
