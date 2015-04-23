devise.define(['jquery'], function($) {

    /**
     * This calculates the node positions for all
     * the nodes inside of the nodesData
     */
    function calculateNodePositions(nodesView, nodesData)
    {
        nodesView.children().each(function(index, child)
        {
            var cid = $(child).data('node-cid');
            var node = nodesData[cid];
            var body = nodesView.closest('body');

            // groups have nested nodes inside of them
            if (node.binding === 'group')
            {
                for (var i = 0; i < node.data.length; i++)
                {
                    var current = node.data[i];
                    current.position = getCoordinatesForNode(current.key, current.cid, body);
                }
            }

            node.position = (node.binding === 'group')
                ? getCoordinatesForGroupNode(node, body)
                : getCoordinatesForNode(node.key, node.cid, body);

            node.position.side = getSideForNode(node.position);
        });
    }

    /**
     * This adjusts the nodes in the DOM
     * to their new positions
     */
    function adjustNodeDOMPositions(nodesView, nodesData)
    {
        nodesView.children().each(function(index, child)
        {
            var el = $(child);
            var cid = el.data('node-cid');
            var node = nodesData[cid];

            el.css('top', node.position.top);
            el.removeClass('left right');
            el.addClass(node.position.side);
        });
    }

    /**
     * Get the coordinates for a cid or key inside this view
     */
    function getCoordinatesForNode(key, cid, view)
    {
        var hidden, coordinates;
        var placeholder = view.find('[data-dvs-placeholder="' + key + '"]').last();
        var element = view.find('[data-devise-' + cid + ']').first();

        if (element.length)
        {
            hidden = !element.is(':visible');

            if (hidden) element.show();
            coordinates = element.offset();
            if (hidden) element.hide();

            if (typeof coordinates === 'object' && coordinates.top) return coordinates;
            return getCoordinatesFromParent(element);
        }

        placeholder.show();
        coordinates = placeholder.offset();
        placeholder.hide();

        if (typeof coordinates === 'object' && coordinates.top) return coordinates;

        return getCoordinatesFromParent(placeholder);
    }

    /**
     * finds the first node in the group with a position
     */
    function getCoordinatesForGroupNode(groupNode, view)
    {
        var position = false;

        $.each(groupNode.data.categories, function(index, category)
        {
            for (var i = 0; i < category.nodes.length; i++)
            {
                var node = category.nodes[i];
                var nodePosition = getCoordinatesForNode(node.key, node.cid, view);
                if (!position) position = nodePosition;
            }
        });

        return position;
    }

    /**
     * Pick left or right side for this node
     */
    function getSideForNode(coordinates)
    {
        var half = $(window).width() / 2;

        if (typeof coordinates == 'undefined') return 'float';

        if (coordinates.left > half) return 'right';

        return 'left';
    }

    /**
     * This makes sure that no two nodes touch each other
     */
    function solveNodeCollisions(nodesView, nodesData)
    {
        var nodes = nodesData;

        var view = nodesView.closest('body');

        for (var i = 0; i < nodes.length; i++)
        {
            for (var j = 0; j < nodes.length; j++)
            {
                if (i != j && hasNodeCollision(nodes[i], nodes[j]))
                {
                    nodes[j].position.top += solveNodeCollision(nodes[i], nodes[j]);
                }
            }
        }
    }

    /**
     * Figures out if we have a node collision
     */
    function hasNodeCollision(node1, node2)
    {
        var nodeHeight = 82;

        if (node1.position.side != node2.position.side) return false;

        return Math.abs(node1.position.top - node2.position.top) < nodeHeight;
    }

    /**
     * Fix the node collision
     */
    function solveNodeCollision(node1, node2)
    {
        return Math.abs(node1.position.top - node2.position.top);
    }

    /**
     * Get the coordinates from the parent of this view,
     * sometimes stuff is nested in some deep DOM tree
     * and that stuff is hidden... so hidden elements we
     * cannot get coordinates for because it is hidden.
     * we could try unhiding it but that would give us
     * some flashy stuff on the page...
     */
    function getCoordinatesFromParent(placeholder)
    {
        var sanity = 50;
        var child = placeholder;
        var parent = placeholder.parent();
        var coordinates;

        while (child !== parent && sanity--)
        {
            coordinates = parent.offset();
            if (typeof coordinates === 'object' && coordinates.top) return coordinates;
            child = parent;
            parent = child.parent();
        }

        return { top: 0, left: 0 };
    }

    /**
     * recalculates node positions
     */
    return {
    	recalculateNodePositions: function(nodesView, nodesData)
    	{
            calculateNodePositions(nodesView, nodesData);
            solveNodeCollisions(nodesView, nodesData);
            adjustNodeDOMPositions(nodesView, nodesData);
    	}
	};

});