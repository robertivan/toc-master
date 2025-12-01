(function (blocks, element, data, components) {
    var el = element.createElement;
    var registerBlockType = blocks.registerBlockType;
    var useSelect = data.useSelect;
    var useState = element.useState;
    var useEffect = element.useEffect;

    registerBlockType('tbrv/toc', {
        title: 'TOC Builder by RobertIvan',
        icon: 'list-view',
        category: 'widgets',
        edit: function (props) {
            // Live Preview Logic
            // We need to scan the editor for headings.

            const headings = useSelect(select => {
                const blocks = select('core/block-editor').getBlocks();
                const headingBlocks = [];

                const traverseBlocks = (innerBlocks) => {
                    innerBlocks.forEach(block => {
                        if (block.name === 'core/heading') {
                            headingBlocks.push({
                                level: block.attributes.level,
                                content: block.attributes.content
                            });
                        }
                        if (block.innerBlocks) {
                            traverseBlocks(block.innerBlocks);
                        }
                    });
                };

                traverseBlocks(blocks);
                return headingBlocks;
            }, []);

            // Render the TOC
            if (headings.length === 0) {
                return el('div', { className: 'tbrv-container' },
                    el('p', {}, 'Add some headings to see the Table of Contents.')
                );
            }

            return el('div', { className: 'tbrv-container' },
                el('div', { className: 'tbrv-header' },
                    el('span', { className: 'tbrv-title' }, 'Table of Contents')
                ),
                el('ul', { className: 'tbrv-list' },
                    headings.map((heading, index) => {
                        return el('li', { key: index, style: { marginLeft: (heading.level - 2) * 20 + 'px' } },
                            el('a', { href: '#' }, heading.content.replace(/<[^>]*>?/gm, '')) // Strip HTML
                        );
                    })
                )
            );
        },
        save: function () {
            return null; // Rendered in PHP
        },
    });
})(window.wp.blocks, window.wp.element, window.wp.data, window.wp.components);
