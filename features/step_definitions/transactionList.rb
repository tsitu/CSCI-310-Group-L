Given(/^I see transactions$/) do
	visit("https://localhost/login/")
	find('#login-username').set('test@gmail.com')
	find('#login-password').set('test')
	page.execute_script("$('#login-button').click()")
	list = Array.new
	list = find('#transaction-list').all('li')
	@size = list.size
end


Then(/^the size is greater than 0$/) do
	@size.should be > 0
end