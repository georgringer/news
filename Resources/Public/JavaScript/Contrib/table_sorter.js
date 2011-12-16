Element.addMethods({
  collectTextNodes: function(element) {
    return $A($(element).childNodes).collect( function(node) {
      return (node.nodeType==3 ? node.nodeValue :
        (node.hasChildNodes() ? Element.collectTextNodes(node) : ''));
    }).flatten().join('');
  },

  removeClassNames: function(element, array) {
    if (!(element = $(element))) return;
    array.each(function(e) { element.removeClassName(e);})
    return element;
  }
});

var Comparator = {
  compareFunction: function(sortType) {
    switch(true) {
      case sortType.include("integer"):
        return Comparator.complexCompare(function(a) { return parseFloat(a.replace(/^.*?([\d\.]+).*$/,"$1")) });
      case sortType.include("date"):
        return Comparator.complexCompare(Date.parse)
      case sortType.include("float"):
        return Comparator.complexCompare(function(a) { return parseFloat(a.replace(/^.*?([\d\.]+).*$/,"$1")) });
      default:
        return Comparator.complexCompare(function(a) { return a.toLowerCase(); });
    }

  },

  simpleCompare: function(a,b) {
    return a < b ? -1 : a == b ? 0 : 1;
  },

  complexCompare: function(compareFn) {
    return function(a, b) {return Comparator.simpleCompare(compareFn(a), compareFn(b)); }
  }
};

Object.extend(Array.prototype, {
  sortByColumnHead: function(sortType, iterator, context) {
    iterator = iterator.bind(context);
    return this.map(function(value, index) {
      return {value: value, criteria: iterator(value, index)};
    }).sort(function(left, right) {
      var a = left.criteria, b = right.criteria;
      sortFn = Comparator.compareFunction(sortType);
      return sortFn(a, b);
    }).pluck('value');
  }
})

var TableSorter = Class.create({
  initialize: function(element, defaultSortIndex) {
    this.element = $(element);
    this.sortIndex = defaultSortIndex;
    this.sortOrder = 'asc';
    this.initDOMReferences();
    this.initEventHandlers();
  }, // initialize

  initDOMReferences: function() {
    var head = this.element.down('thead');
    var body = this.element.down('tbody');
    if (!head || !body)
      throw 'Table must have a head and a body to be sortable.';
    this.headers = head.down('tr').childElements();
    this.headers.each(function(e, i) {
      if(!e.className.include('nosort')) //dont sort a column by setting the unsort property(isn't it GREAT)
        e._colIndex = i;
    });
    this.body = body;
  }, // initDOMReferences

  initEventHandlers: function() {
    this.handler = this.handleHeaderClick.bind(this);
    this.element.observe('click', this.handler);
  }, // initEventHandlers



  handleHeaderClick: function(e) {
    var element = e.element();
    if (!('_colIndex' in element)) {
      element = element.ancestors().find(function(elt) {
        return '_colIndex' in elt;
      });
      if (!((element) && '_colIndex' in element))
        return;
    }
    this.sort(element._colIndex, (element.className || ''));
  }, // handleHeaderClick

  //call this function when a row is added dynamically to make sure the existing sortoder is retained
  resort: function(index, sortType) {
    this.sort(index, sortType);
    //call it again to bring back the original sort order
    this.sort(index, sortType);
  },

  adjustSortMarkers: function(index) {
    if (this.sortIndex != -1)
      this.headers[this.sortIndex].removeClassName('sort-' +
        this.sortOrder);
    if (this.sortIndex != index) {
      this.sortOrder = 'asc';
      this.sortIndex = index;
    } else
      this.sortOrder = ('asc' == this.sortOrder ? 'desc' : 'asc');
    this.headers[index].addClassName('sort-' + this.sortOrder);
  }, // adjustSortMarkers

  sort: function(index, sortType) {
    this.adjustSortMarkers(index);
    var rows = this.body.childElements();
    rows = rows.sortByColumnHead(sortType, function(row) {
      return row.childElements()[this.sortIndex].collectTextNodes();
    }.bind(this));

    if ('desc' == this.sortOrder)
      rows.reverse();

    rows.reverse().each(function(row, index) {
      if (index > 0)
        this.body.insertBefore(row, rows[index - 1]);
    }.bind(this));

    rows.reverse().each(function(row, index) {
      row.removeClassNames(['odd', 'even']);
      (1 == index % 2) ? row.addClassName('odd') : row.addClassName('even');
    });
  } // sort
}); // TableSorter



/**********************************/
var TableSortObserver = {
  sortableTables : {},

  bindEventsToTableRow: function(table_id) {
    defaultSortIndex = -1;
    $(table_id).down('thead').down('tr').childElements().each(function(e, i) {
      if(e.className.include('sort')) defaultSortIndex = i
    });
    this.sortableTables[table_id] = new TableSorter(table_id, defaultSortIndex);
  }
}