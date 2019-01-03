CRM.$(function($) {
  $('div#participantSearch table.selector tr.crm-event').each(function() {
    var participantId = $(this).prop('id').replace('rowid', '');
    var rowId = $(this).prop('id');
    //console.log('this: ', this);
    //console.log('participantId: ', participantId);

    if (participantId) {
      CRM.api3('Participant', 'getvalue', {
        "return": "contact_id",
        "id": participantId
      }).done(function (contactId) {
        //console.log('contactId: ', contactId);
        CRM.api3('Contact', 'getvalue', {
          "return": "current_employer",
          "id": contactId
        }).done(function(org) {
          //console.log('org: ', org);
          if (org.result === 0) {org.result = ''}

          //using standard js because it works better...
          var row = document.getElementById(rowId);
          var x = row.insertCell(3);
          x.innerHTML = org.result;
        });
      });
    }
  })
});
