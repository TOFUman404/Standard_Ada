import $ from 'jquery';
import Store from '@kanety/js-store';

import { NAMESPACE } from './consts';

const DEFAULTS = {
  expander: null,
  collapser: null,
  opened: 'all',
  margin: 20,
  iconPosition: ':first',
  iconTemplate: '<span />',
  store: null,
  storeKey: null
};

export default class SimpleTreeTable {
  constructor(element, options = {}) {
    this.options = $.extend({}, DEFAULTS, options);

    this.$table = $(element);
    this.$expander = $(this.options.expander);
    this.$collapser = $(this.options.collapser);

    if (this.options.store && this.options.storeKey) {
      this.store = new Store({
        type: this.options.store,
        key: this.options.storeKey
      });
    }

    this.init();
    this.load();
  }

  init() {
    this.$table.addClass(NAMESPACE);
    this.build();
    this.unbind();
    this.bind();
  }

  destroy() {
    this.$table.removeClass(NAMESPACE);
    this.$table.find('.tree-icon').remove();
    this.nodes().removeClass('tree-empty tree-opened tree-closed');

    this.unbind();
  }

  build() {
    this.nodes().not('[data-node-depth]').each((i, node) => {
      let $node = $(node);
      let depth = this.depth($node);
      $node.data('node-depth', depth);
      if (depth == 1) {
        $node.addClass('tree-root');
      }
    });

    this.nodes().filter((i, node) => {
      return $(node).find(this.options.iconPosition).find('.tree-handler').length == 0;
    }).each((i, node) => {
      let $node = $(node);
      let depth = this.depth($node);
      let margin = this.options.margin * (depth - 1);
      let $icon = $(this.options.iconTemplate).addClass('tree-handler tree-icon').css('margin-left', `${margin}px`);
      $node.find(this.options.iconPosition).prepend($icon);
    });

    this.nodes().not('.tree-empty, .tree-opened, .tree-closed').each((i, node) => {
      let $node = $(node);
      if (!this.hasChildren($node)) {
        $node.addClass('tree-empty');
      } else if (this.opensDefault($node)) {
        $node.addClass('tree-opened');
      } else {
        $node.addClass('tree-closed');
      }
    });

    this.nodes().filter('.tree-opened').each((i, node) => {
      this.show($(node));
    });
    this.nodes().filter('.tree-closed').each((i, node) => {
      this.hide($(node));
    });
  }

  opensDefault($node) {
    let opened = this.options.opened;
    return opened && (opened == 'all' || opened.indexOf($node.data('node-id')) != -1);
  }

  bind() {
    this.$expander.on(`click.${NAMESPACE}`, (e) => {
      this.expand();
    });

    this.$collapser.on(`click.${NAMESPACE}`, (e) => {
      this.collapse();
    });

    this.$table.on(`click.${NAMESPACE}`, 'tr .tree-handler', (e) => {
      let $node = $(e.currentTarget).closest('tr');
      if ($node.hasClass('tree-opened')) {
        this.close($node);
      } else {
        this.open($node);
      }
    });
  }

  unbind() {
    this.$expander.off(`.${NAMESPACE}`);
    this.$collapser.off(`.${NAMESPACE}`);
    this.$table.off(`.${NAMESPACE} node:open node:close`);
  }

  expand() {
    this.nodes().each((i, node) => {
      this.show($(node));
    });
    this.save();
  }

  collapse() {
    this.nodes().each((i, node) => {
      this.hide($(node));
    });
    this.save();
  }

  nodes() {
    return this.$table.find('tr[data-node-id]');
  }

  depth($node) {
    let d = $node.data('node-depth');
    if (d) {
      return d;
    }

    let $parent = this.findByID($node.data('node-pid'));
    if ($parent.length != 0) {
      return this.depth($parent) + 1;
    } else {
      return 1;
    }
  }

  open($node) {
    this.show($node);
    this.save();

    $node.trigger('node:open', [$node]);
  }

  show($node) {
    if (!$node.hasClass('tree-empty')) {
      $node.removeClass('tree-closed').addClass('tree-opened');
      this.showDescs($node);
    }
  }

  showDescs($node) {
    let $children = this.findChildren($node);
    $children.each((i, child) => {
      let $child = $(child);
      $child.show();
      if ($child.hasClass('tree-opened')) {
        this.showDescs($child);
      }
    });
  }

  close($node) {
    this.hide($node);
    this.save();

    $node.trigger('node:close', [$node]);
  }

  hide($node) {
    if (!$node.hasClass('tree-empty')) {
      $node.removeClass('tree-opened').addClass('tree-closed');
      this.hideDescs($node);
    }
  }

  hideDescs($node) {
    let $children = this.findChildren($node);
    $children.each((i, child) => {
      let $child = $(child);
      $child.hide();
      this.hideDescs($child);
    });
  }

  hasChildren($node) {
    return this.findChildren($node).length != 0;
  }

  findChildren($node) {
    let pid = $node.data('node-id');
    return this.$table.find(`tr[data-node-pid="${pid}"]`);
  }

  findDescendants($node, descendants = []) {
    let children = this.findChildren($node)
    descendants.push(children);
    children.each((i, child) => {
      this.findDescendants($(child), descendants);
    })
    return descendants;
  }

  findByID(id) {
    return this.$table.find(`tr[data-node-id="${id}"]`);
  }

  openByID(id) {
    this.open(this.findByID(id));
  }

  closeByID(id) {
    this.close(this.findByID(id));
  }

  load() {
    if (!this.store) return;

    let ids = this.store.get();
    if (!ids) return;

    this.nodes().each((i, node) => {
      this.show($(node));
    });
    this.nodes().filter((i, node) => {
      return ids.indexOf($(node).data('node-id')) != -1;
    }).each((i, node) => {
      this.hide($(node));
    });
  }

  save() {
    if (!this.store) return;

    let ids = this.nodes().filter('.tree-closed').map((i, node) => {
      return $(node).data('node-id');
    }).get();

    this.store.set(ids)
  }

  static getDefaults() {
    return DEFAULTS;
  }

  static setDefaults(options) {
    return $.extend(DEFAULTS, options);
  }
}
