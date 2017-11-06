<script type="text/template" data-grid="news" data-template="results">

	<% _.each(results, function(r) { %>

	<tr data-grid-row
		<% if(r.active ==  0) { %>
			<%=  'class="alert-danger"' %>
		<% } %>
		<% if(r.active == 1) { %>
			<%=  'class="alert-success"' %>
		<% } %>
	>
		<td><input content="id" input data-grid-checkbox="" name="entries[]" type="checkbox" value="<%= r.id %>"></td>
		<td><img src="<?php echo Asset("<%=r.image%>") ?>" width="100" height="100" class="img-thumbnail"></td>
		<td><%= r.newscategory_id %></td>
		<td><%= r.title_en %></td>
		<% _.each( r.title_languages, function( language ) { %>
			<td><%= language %></td>
		<% }); %>
		<td><%= r.date %></td>
		<td><%= r.views %></td>
		<td>
			<% if (r.has_video == 1) { %>
			<i style="color: green;" class="fa fa-2x fa-check-circle-o"></i>
			<% } else { %>
			<i style="color: red;" class="fa fa-2x fa-times"></i>
			<% } %>
		</td>
		<td>
			<% if (r.has_sound == 1) { %>
			<i style="color: green;" class="fa fa-2x fa-check-circle-o"></i>
			<% } else { %>
			<i style="color: red;" class="fa fa-2x fa-times"></i>
			<% } %>
		</td>
		<td>
			<% if (r.active == 1) { %>
			<i style="color: green;" class="fa fa-2x fa-check-circle-o"></i>
			<% } else { %>
			<i style="color: red;" class="fa fa-2x fa-times"></i>
			<% } %>
		</td>
		<td>
			<% if (r.home == 1) { %>
			<i style="color: green;" class="fa fa-2x fa-check-circle-o"></i>
			<% } else { %>
			<i style="color: red;" class="fa fa-2x fa-times"></i>
			<% } %>
		</td>
		<td><%= r.created_by %></td>
		<td><%= r.updated_by %></td>
		<td><%= moment(r.created_at).format('DD/MM/YYYY') %></td>
		<td><a class="btn btn-xs btn-success fa fa-pencil" href="<%= r.edit_uri %>"></a></td>
	</tr>

<% }); %>

</script>
