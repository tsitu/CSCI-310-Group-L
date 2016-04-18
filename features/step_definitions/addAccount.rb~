Given(/^user has an account csv file$/) do 
	Capybara.reset_sessions!
	visit("https://localhost/www/login/")
	find('#login-username').set('test@gmail.com')
	find('#login-password').set('test')
	page.execute_script("$('#login-button').click()")
	list = Array.new
	list = find('#account-list').all('li')
	@size = list.size
end 

When(/^user adds it through browse$/) do 
	attach_file('#csv-file', File.absolute_path('test.csv'))
	page.execute_script("$('.new-account-button').click()")
end 

Then(/^account list increases$/) do 
	expect(find('#account-list')).to have_selector('li', count: @size + 1)
end 
