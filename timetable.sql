DROP TABLE fact;
DROP TABLE time;

CREATE TABLE time(
	time_id		date,
	day		char(1),
	week		varchar(5),
	month		varchar(2),
	quarter		char(1),
	year		varchar(4),
	PRIMARY KEY(time_id));
commit;

/* Pulled from information provided at http://www.oraclenerd.com/2009/03/how-to-populate-your-time-dimension.html */
INSERT INTO time ( time_id, day, week, month, quarter, year )
SELECT
	TRUNC ( sd + rn ) time_id,
	TO_CHAR( sd + rn, 'D' ) day,
	TO_CHAR(ceil((TO_NUMBER(TO_CHAR(sd+rn, 'DD')) - 1 + TO_NUMBER(TO_CHAR(sd+rn-TO_CHAR(sd+rn, 'DD')+1, 'D')))/7)) week,
	TO_CHAR( sd + rn, 'MM' ) month,
	TO_CHAR( sd + rn, 'Q' ) quarter,
	TO_CHAR( sd + rn, 'YYYY' ) year
	FROM
  		( 
    		SELECT 
      		TO_DATE( '12/31/2005', 'MM/DD/YYYY' ) sd,
      		rownum rn
    		FROM dual
      		CONNECT BY level <= 6575
  		)
commit;


CREATE TABLE fact(
		sensor_id	int,
		id		int,
		time_id		date,
		svalue		float,
		PRIMARY KEY (sensor_id, id, time_id),
		FOREIGN KEY (sensor_id) REFERENCES sensors,
		FOREIGN KEY (id) REFERENCES scalar_data,
		FOREIGN KEY (time_id) REFERENCES time);
commit;

INSERT INTO fact ( sensor_id, id, time_id, svalue)
SELECT	s.sensor_id, c.id, t.time_id, c.value
FROM	sensors s, scalar_data c, time t
WHERE	s.sensor_id = c.sensor_id
AND	t.time_id LIKE c.date_created;

commit;

INSERT INTO persons (person_id, first_name, last_name, address, email, phone)
VALUES('1', 'David', 'Admin', '1122 BoogieWoogie Ave', 'davidadmin@test.ca', '7801258764');
commit;
INSERT INTO users(user_name, password, role, person_id, date_registered)
VALUES('admin', 'admin', 'a', '1', TO_DATE('11/25/2015', 'MM/DD/YYYY'));
commit;
