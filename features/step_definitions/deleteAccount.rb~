Given(/^User has an account$/) do
	Capybara.reset_sessions!
	visit("https://localhost/CSCI-310-Group-L/www/login/")
	find('#login-username').set('test@gmail.com')
	find('#login-password').set('test')
	page.execute_script("$('#login-button').click()")
	list = Array.new
	list = find('#account-list').all('li')
	@size = list.size
end

When(/^user clicks on the delete button$/) do
	page.execute_script("$('#1').click()")
	page.execute_script("$('.remove-account-confirm').click()")
end 

Then(/^account list shortens$/) do
	expect(find('#account-list')).to have_selector('li', count: @size - 1)
end
