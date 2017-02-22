package test12;

public class MakeCommaTest {
	
	//숫자 형태의 문자열을 콤마가 포함된 금액 표기식 문자열로
	public static void main(String args[]){
		
		long sTime = System.currentTimeMillis();		
		System.out.println(makeComma("-234345523432223252323.5562345")); //-234,345,523,432,223,252,323.5562345
		long eTime = System.currentTimeMillis() - sTime;
		System.out.println(eTime + " ms");
		
		System.out.println(makeComma("20000000")); //20,000,000
		System.out.println(makeComma("2000000")); //2,000,000		
	}
	
	private static String makeComma(String num) {
		
		int cnt = 0;
		//문자열 담기 위한 변수
		String str = "";
		//소수점 이하 담기 위한 변수
		String strTail = "";
		//비교 기호
		String op = "-, +";
		
		//소수점		
		if (num.indexOf(".") >= 0) {
			strTail = num.substring(num.indexOf(".")); //소수점이 있으면(소수점위치반환)소수점 부터 끝까지 추출하여 strTail 변수에 저장
			num = num.substring(0, num.indexOf("."));//소수점이 있으면 숫자를 0부터 소수점 직전까지 가지고 와서 num 변수에 저장		
		}
		
		boolean isOp = false;
		if (op.indexOf(num.charAt(0)) >= 0) // +,- 가 있으면
			isOp = true;
		
		for (int i=num.length()-1; i>=0; i--) //charat i=0 시작
		{
			cnt++;
			System.out.println(str);
			if ( cnt%3 == 0 && i != 0 ) { //,200,000 처리			
				if (isOp && i == 1) str = num.charAt(i)+str; //-,325,436,454,323,425.24 처리
				else str = ","+num.charAt(i)+str;
			} else {
				str = num.charAt(i)+str;
			}
		}
		
		//System.out.println(str + strTail);
		return str + strTail;
	}	

}

