Given(/^I have logged in$/) do
	visit("https://localhost/login/")
	find('#login-username').set('test@gmail.com')
	find('#login-password').set('test')
	page.execute_script("$('#login-button').click()")
	list = Array.new
	list = find('.category-list').all('li')
	@size = list.size
end

Then(/^I should see four monthly budgets$/) do
	@size.should be == 4
end