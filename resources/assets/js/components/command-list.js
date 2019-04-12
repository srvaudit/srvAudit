Vue.component('srvaudit-command-list',{
	template:`
		<table class="table table-sm nowrapp">
			<thead>
				<tr>
					<th>Command</th>
					<th>Directory</th>
					<th>Return</th>
					<th>Pipes</th>
					<th>When</th>
					<th>Duration</th>
				</tr>
			</thead>
			<tbody>
				<tr v-for="(command, index) in commands" :key="index">
					<td>{{ command.cmd }}</td>
					<td>{{ command.cwd }}</td>
					<td>{{ command.rval }}</td>
					<td>{{ command.pipes }}</td>
					<td><time class="timeago" :datetime="command.begin"></time></td>
					<td>{{ command.duration }}s</td>
				</tr>
			</tbody>
			<srvaudit-commands></srvaudit-commands>
		</table>
    `,
	props: ['command'],
	data() {
		return {
			commands: []
		};
	},
    created: function() {
        var pathArray = window.location.pathname.split( '/' );
        var sid = pathArray[2];
		axios.get(`/sessions/${sid}`).then(response => {
            var self = this;
			Object.keys(response.data.commands || {}).forEach(function (key) {
				var data = response.data.commands[key];
				// get command duration
				var begin = moment(data.start).subtract(7, 'hours');
				var stop = moment(data.end).subtract(7, 'hours');
				data.duration = stop.diff(begin, 'seconds');
				// push command to data stack
				// update human times in command table
                self.commands.push(data);
				Vue.nextTick(function() {
					jQuery("time.timeago").timeago();
				}); 
			});
		});
    },  
});

Vue.component('srvaudit-commands', {
    template: `
        <tbody>
            <tr v-for="(command, index) in commands" :key="index">
                <td>{{ command.cmd }}</td>
                <td>{{ command.cwd }}</td>
                <td>{{ command.rval }}</td>
                <td>{{ command.pipes }}</td>
                <td><time class="timeago" :datetime="command.begin"></time></td>
                <td>{{ command.duration }}s</td>
            </tr>
        </tbody>
    `,
    props: ['command'],
    data() {
        return {
            commands: []  
        };
    },
    mounted: function() {
        var pathArray = window.location.pathname.split( '/' );
        var sid = pathArray[2];
        Echo.private(`session.${sid}.commands`)
            .listen('CommandReceived', (data) => {
                // console.log(data.command);
                // get command duration
                var begin = moment(data.command.start).subtract(7, 'hours');
                var stop = moment(data.command.end).subtract(7, 'hours');
                data.command.duration = stop.diff(begin, 'seconds');
                // push command to data stack
                this.commands.push(data.command);
                // update human times in command table
                Vue.nextTick(function() {
                    jQuery("time.timeago").timeago();
                }); 
            });
    },  
});

