
<table>
  <thead>
    <tr>
      <th>Název</th>
      <th>IP k STB</th>
    </tr>
  </thead>
  <tbody>
    @foreach($channels as $channel)
      <tr>
        <td>{{ $channel->nazev }}</td>
        <td>{{ $channel->ipKstb }}</td>
      </tr>
    @endforeach
  </tbody>
</table>

