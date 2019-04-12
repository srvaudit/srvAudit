# bootstrap-breakpoints
Missing Bootstrap breakpoints at the lower (480px - 768px) and higher end (1400+)

Here are two common Bootstrap breakpoints which are missing in v3 but are often very useful.
They include full mixin support for semantic layouts but you will have to compile LESS 
if you want to use the CSS directly from your markup.

In LESS files include files `ms.less` and `xl.less` after the base Bootstrap import like this:

    @import "../bootstrap/less/bootstrap.less";
    @import "../bootstrap/ms/ms";
    @import "../bootstrap/xl/xl";

(correct paths as necessary)

You can put the files separately to the Bootstrap source allowing you to update Bootstrap indenpendently (as long as they don't break the overall class structure/mixins which, unfortunately, they do a lot).

Hope you find these helpful. Provided free of charge without warranty of any kind.

Thanks to these guys for their help in compiling these together:

- https://github.com/twbs/bootstrap/issues/10203
- http://spin.atomicobject.com/2015/03/06/bootstrap-add-xl-grid-size-option/
- http://stackoverflow.com/questions/23264516/creating-a-new-twitter-bootstrap-responsive-column-size
