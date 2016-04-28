Given(/^user clicks on the account and transactions$/) do
	visit("https://localhost/login/")
	find('#login-username').set('test@gmail.com')
	find('#login-password').set('test')
	page.execute_script("$('#login-button').click()")
	list = Array.new
	list = find('#account-list').all('li')
	@size = list.size
end

Then(/^user should be able to see a graph$/) do
	if(@size > 0)
		page.execute_script("$('#account-option fa fa-line-chart').click()")
		expect(page).to have_selector('#graph', visible:true)
		list2 = Array.new
		list2 = find('.highcharts-axis-labels.highcharts-yaxis-labels').all('text')
		@size2 = list2.size
		@size2.should be > 0

		#page.should have_css('svg')
	end
end


